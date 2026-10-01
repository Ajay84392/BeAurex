<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'onboarding_step')) { $table->string('onboarding_step')->default('account_registration'); }
            if (! Schema::hasColumn('users', 'onboarding_completed_at')) { $table->timestamp('onboarding_completed_at')->nullable(); }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_step', 'onboarding_completed_at']);
        });
    }
};
