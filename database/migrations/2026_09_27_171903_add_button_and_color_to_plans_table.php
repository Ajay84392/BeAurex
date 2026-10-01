<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('plans')) Schema::table('plans', function (Blueprint $table) {
            if (! Schema::hasColumn('plans', 'button_text')) { $table->string('button_text')->default('Start 2-Day Trial')->after('type'); }
            if (! Schema::hasColumn('plans', 'button_link')) { $table->string('button_link')->default('/merchant/register')->after('button_text'); }
            if (! Schema::hasColumn('plans', 'color')) { $table->string('color')->nullable()->after('button_link'); }
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['button_text', 'button_link', 'color']);
        });
    }
};
