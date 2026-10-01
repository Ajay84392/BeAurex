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
        if (Schema::hasTable('users')) Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) { $table->string('role')->default('customer')->after('email'); } // admin, merchant, customer
            if (! Schema::hasColumn('users', 'otp')) { $table->string('otp')->nullable()->after('password'); }
            if (! Schema::hasColumn('users', 'otp_expires_at')) { $table->timestamp('otp_expires_at')->nullable()->after('otp'); }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'otp', 'otp_expires_at']);
        });
    }
};
