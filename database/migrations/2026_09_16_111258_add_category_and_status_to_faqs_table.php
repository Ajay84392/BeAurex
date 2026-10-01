<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('faqs')) Schema::table('faqs', function (Blueprint $table) {
            if (! Schema::hasColumn('faqs', 'category')) { $table->string('category')->default('General')->after('answer'); }
            if (! Schema::hasColumn('faqs', 'status')) { $table->string('status')->default('Published')->after('category'); }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['category', 'status']);
        });
    }
};
