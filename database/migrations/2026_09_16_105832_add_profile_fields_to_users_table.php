<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'last_name')) { $table->string('last_name')->nullable(); }
            if (! Schema::hasColumn('users', 'phone')) { $table->string('phone')->nullable(); }
            if (! Schema::hasColumn('users', 'photo')) { $table->string('photo')->nullable(); }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'phone', 'photo']);
        });
    }
};
