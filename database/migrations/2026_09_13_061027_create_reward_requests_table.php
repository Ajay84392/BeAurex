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
        if (! Schema::hasTable('reward_requests')) Schema::create('reward_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('reward_type')->default('Coupon'); // 'Coupon', 'FREE'
            $table->string('reward_title')->nullable(); // '30% OFF', 'COFFEE'
            $table->string('reward_description')->nullable();
            $table->string('code')->nullable();
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_requests');
    }
};
