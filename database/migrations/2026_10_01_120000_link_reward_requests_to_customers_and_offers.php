<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A reward request now records which customer claimed which offer and how many coins it cost,
 * so coin balances, the customer's rewards page and the merchant's approvals all agree.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reward_requests')) Schema::table('reward_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('reward_requests', 'customer_id')) { $table->unsignedBigInteger('customer_id')->nullable()->after('business_id')->index(); }
            if (! Schema::hasColumn('reward_requests', 'offer_id')) { $table->unsignedBigInteger('offer_id')->nullable()->after('customer_id')->index(); }
            if (! Schema::hasColumn('reward_requests', 'coins_spent')) { $table->unsignedInteger('coins_spent')->default(0)->after('offer_id'); }
        });
    }

    public function down(): void
    {
        Schema::table('reward_requests', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['offer_id']);
            $table->dropColumn(['customer_id', 'offer_id', 'coins_spent']);
        });
    }
};
