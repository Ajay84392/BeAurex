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
        if (! Schema::hasTable('referrals')) Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->string('referred_email')->nullable();
            $table->foreignId('referred_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('reward_coins')->default(0);
            $table->enum('status', ['Pending', 'Completed', 'Expired'])->default('Pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
