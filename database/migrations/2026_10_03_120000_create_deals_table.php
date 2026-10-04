<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Deals the admin saves on Manage Deals (/admin/claims), and the one set on each merchant ("Set a Deal").
     */
    public function up(): void
    {
        if (! Schema::hasTable('deals')) {
            Schema::create('deals', function (Blueprint $table) {
                $table->id();
                $table->string('deal_name');
                $table->string('coupon_code');
                $table->string('plan_name')->nullable();
                $table->string('plan_type')->nullable();
                $table->string('state')->nullable();
                $table->decimal('bonus_amount', 10, 2)->nullable();
                $table->decimal('discount_amount', 10, 2)->nullable();
                $table->decimal('discount_percentage', 5, 2)->nullable();
                $table->date('validity_date');
                $table->unsignedInteger('maximum_usage')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('businesses') && ! Schema::hasColumn('businesses', 'deal_id')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->foreignId('deal_id')->nullable()->constrained('deals')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('businesses', 'deal_id')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropConstrainedForeignId('deal_id');
            });
        }

        Schema::dropIfExists('deals');
    }
};
