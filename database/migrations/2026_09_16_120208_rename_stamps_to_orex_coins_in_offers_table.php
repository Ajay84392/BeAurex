<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('offers')) Schema::table('offers', function (Blueprint $table) {
            if (Schema::hasColumn('offers', 'stamps') && ! Schema::hasColumn('offers', 'aurex_coins')) { $table->renameColumn('stamps', 'aurex_coins'); }
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->renameColumn('aurex_coins', 'stamps');
        });
    }
};
