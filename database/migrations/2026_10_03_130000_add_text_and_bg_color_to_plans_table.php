<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optional text and background colours for a plan's card on the home page (#rrggbb; null keeps the default look).
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (! Schema::hasColumn('plans', 'text_color')) { $table->string('text_color', 7)->nullable(); }
            if (! Schema::hasColumn('plans', 'bg_color')) { $table->string('bg_color', 7)->nullable(); }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['text_color', 'bg_color']);
        });
    }
};
