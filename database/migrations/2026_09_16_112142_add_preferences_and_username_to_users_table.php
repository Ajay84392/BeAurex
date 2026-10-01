<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) { $table->string('username')->nullable()->unique()->after('email'); }
            if (! Schema::hasColumn('users', 'language')) { $table->string('language')->default('English')->after('photo'); }
            if (! Schema::hasColumn('users', 'timezone')) { $table->string('timezone')->default('Asia/Kolkata')->after('language'); }
            if (! Schema::hasColumn('users', 'date_format')) { $table->string('date_format')->default('d M, Y')->after('timezone'); }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'language', 'timezone', 'date_format']);
        });
    }
};
