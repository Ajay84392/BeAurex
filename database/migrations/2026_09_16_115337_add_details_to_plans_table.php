<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('plans')) Schema::table('plans', function (Blueprint $table) {
            if (! Schema::hasColumn('plans', 'type')) { $table->string('type')->nullable()->after('billing_cycle'); }
            if (! Schema::hasColumn('plans', 'short_description')) { $table->string('short_description', 150)->nullable()->after('type'); }
            if (! Schema::hasColumn('plans', 'detailed_description')) { $table->text('detailed_description')->nullable()->after('short_description'); }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['type', 'short_description', 'detailed_description']);
        });
    }
};
