<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow the same email to hold one account per role (customer, merchant, admin).
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) Schema::table('users', function (Blueprint $table) {
            if (Schema::hasIndex('users', 'users_email_unique')) { $table->dropUnique('users_email_unique'); }
            if (! Schema::hasIndex('users', ['email', 'role'], 'unique')) { $table->unique(['email', 'role']); }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email', 'role']);
            $table->unique('email');
        });
    }
};
