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
        if (Schema::hasTable('businesses')) Schema::table('businesses', function (Blueprint $table) {
            if (! Schema::hasColumn('businesses', 'auto_approval')) { $table->boolean('auto_approval')->default(false)->after('complimentary'); }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('auto_approval');
        });
    }
};
