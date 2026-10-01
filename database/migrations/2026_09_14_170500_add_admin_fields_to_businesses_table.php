<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('businesses')) Schema::table('businesses', function (Blueprint $table) {
            if (! Schema::hasColumn('businesses', 'payment_status')) { $table->string('payment_status')->default('Trial'); }
            if (! Schema::hasColumn('businesses', 'payment_date')) { $table->timestamp('payment_date')->nullable(); }
            if (! Schema::hasColumn('businesses', 'payment_amount')) { $table->decimal('payment_amount', 10, 2)->nullable(); }
            if (! Schema::hasColumn('businesses', 'plan')) { $table->string('plan')->default('Trial Plan'); }
            if (! Schema::hasColumn('businesses', 'plan_valid_till')) { $table->timestamp('plan_valid_till')->nullable(); }
            if (! Schema::hasColumn('businesses', 'status')) { $table->string('status')->default('Trial'); }
            if (! Schema::hasColumn('businesses', 'complimentary')) { $table->boolean('complimentary')->default(false); }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status', 'payment_date', 'payment_amount',
                'plan', 'plan_valid_till', 'status', 'complimentary',
            ]);
        });
    }
};
