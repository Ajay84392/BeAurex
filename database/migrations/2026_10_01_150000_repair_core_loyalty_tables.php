<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * businesses, customers, customer_visits, branches, qr_codes, loyalty_programs and rewards were
 * created by hand and never had migrations, so a server's copy can be missing tables or columns
 * the code relies on (logo, city, pincode, stamp_awarded, …). This creates whatever is missing and
 * adds missing columns. It never drops or changes anything that already exists, so it is safe on
 * every database (live, local or brand new).
 *
 * It also fixes the offers coin column: the code uses "orex_coins", but the offers migrations
 * created "aurex_coins"/"stamps".
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->ensure('businesses', function (Blueprint $t, callable $missing) {
            $missing('user_id') && $t->unsignedBigInteger('user_id')->index();
            $missing('name') && $t->string('name');
            $missing('email') && $t->string('email');
            $missing('phone') && $t->string('phone')->nullable();
            $missing('category') && $t->string('category')->nullable();
            $missing('address') && $t->string('address')->nullable();
            $missing('city') && $t->string('city')->nullable();
            $missing('state') && $t->string('state')->nullable();
            $missing('pincode') && $t->string('pincode')->nullable();
            $missing('logo') && $t->string('logo')->nullable();
            $missing('description') && $t->text('description')->nullable();
            $missing('payment_status') && $t->string('payment_status')->default('Trial');
            $missing('payment_date') && $t->timestamp('payment_date')->nullable();
            $missing('payment_amount') && $t->decimal('payment_amount', 10, 2)->nullable();
            $missing('plan') && $t->string('plan')->default('Trial Plan');
            $missing('plan_valid_till') && $t->timestamp('plan_valid_till')->nullable();
            $missing('status') && $t->string('status')->default('Trial');
            $missing('complimentary') && $t->boolean('complimentary')->default(false);
            $missing('auto_approval') && $t->boolean('auto_approval')->default(false);
            $missing('auto_reward_approval') && $t->boolean('auto_reward_approval')->default(false);
            $missing('deleted_at') && $t->timestamp('deleted_at')->nullable();
        });

        $this->ensure('customers', function (Blueprint $t, callable $missing) {
            $missing('name') && $t->string('name')->nullable();
            $missing('phone') && $t->string('phone')->nullable()->unique();
            $missing('email') && $t->string('email')->nullable()->unique();
            $missing('status') && $t->string('status')->default('Active');
        });

        $this->ensure('branches', function (Blueprint $t, callable $missing) {
            $missing('business_id') && $t->unsignedBigInteger('business_id')->index();
            $missing('name') && $t->string('name');
            $missing('address') && $t->string('address');
            $missing('city') && $t->string('city');
            $missing('state') && $t->string('state')->nullable();
            $missing('pincode') && $t->string('pincode')->nullable();
            $missing('latitude') && $t->decimal('latitude', 10, 8)->nullable();
            $missing('longitude') && $t->decimal('longitude', 11, 8)->nullable();
            $missing('phone') && $t->string('phone')->nullable();
            $missing('opening_hours') && $t->string('opening_hours')->nullable();
            $missing('is_primary') && $t->boolean('is_primary')->default(false);
            $missing('is_active') && $t->boolean('is_active')->default(true);
            $missing('deleted_at') && $t->timestamp('deleted_at')->nullable();
        });

        $this->ensure('qr_codes', function (Blueprint $t, callable $missing) {
            $missing('business_id') && $t->unsignedBigInteger('business_id')->index();
            $missing('branch_id') && $t->unsignedBigInteger('branch_id')->nullable()->index();
            $missing('code') && $t->string('code')->unique();
            $missing('is_active') && $t->boolean('is_active')->default(true);
        });

        $this->ensure('customer_visits', function (Blueprint $t, callable $missing) {
            $missing('customer_id') && $t->unsignedBigInteger('customer_id')->index();
            $missing('business_id') && $t->unsignedBigInteger('business_id')->index();
            $missing('branch_id') && $t->unsignedBigInteger('branch_id')->nullable()->index();
            $missing('qr_code_id') && $t->unsignedBigInteger('qr_code_id')->nullable()->index();
            $missing('scanned_at') && $t->timestamp('scanned_at')->useCurrent();
            $missing('stamp_awarded') && $t->boolean('stamp_awarded')->default(false);
        });

        $this->ensure('loyalty_programs', function (Blueprint $t, callable $missing) {
            $missing('business_id') && $t->unsignedBigInteger('business_id')->index();
            $missing('name') && $t->string('name');
            $missing('description') && $t->text('description')->nullable();
            $missing('stamps_required') && $t->integer('stamps_required')->default(10);
            $missing('reward_description') && $t->string('reward_description');
            $missing('is_active') && $t->boolean('is_active')->default(true);
            $missing('deleted_at') && $t->timestamp('deleted_at')->nullable();
        });

        $this->ensure('rewards', function (Blueprint $t, callable $missing) {
            $missing('business_id') && $t->unsignedBigInteger('business_id')->index();
            $missing('loyalty_program_id') && $t->unsignedBigInteger('loyalty_program_id')->index();
            $missing('name') && $t->string('name');
            $missing('description') && $t->text('description')->nullable();
            $missing('image') && $t->string('image')->nullable();
            $missing('required_stamps') && $t->integer('required_stamps')->default(10);
            $missing('is_active') && $t->boolean('is_active')->default(true);
            $missing('deleted_at') && $t->timestamp('deleted_at')->nullable();
        });

        // The code reads and writes offers.orex_coins.
        if (Schema::hasTable('offers') && ! Schema::hasColumn('offers', 'orex_coins')) {
            $old = collect(['aurex_coins', 'stamps'])->first(fn ($c) => Schema::hasColumn('offers', $c));
            Schema::table('offers', function (Blueprint $t) use ($old) {
                $old ? $t->renameColumn($old, 'orex_coins') : $t->integer('orex_coins')->default(0);
            });
        }
    }

    /** Create the table if it is missing, otherwise add only the columns it lacks. */
    private function ensure(string $table, callable $columns): void
    {
        if (! Schema::hasTable($table)) {
            Schema::create($table, function (Blueprint $t) use ($columns) {
                $t->id();
                $columns($t, fn () => true);
                $t->timestamps();
            });

            return;
        }

        $existing = Schema::getColumnListing($table);
        Schema::table($table, function (Blueprint $t) use ($columns, $existing, $table) {
            $columns($t, fn (string $column) => ! in_array($column, $existing, true));
            if (! in_array('created_at', $existing, true)) {
                $t->timestamps();
            }
        });
    }

    public function down(): void
    {
        // Repair only; nothing to undo.
    }
};
