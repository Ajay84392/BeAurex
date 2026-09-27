<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('button_text')->default('Start 2-Day Trial')->after('type');
            $table->string('button_link')->default('/merchant/register')->after('button_text');
            $table->string('color')->nullable()->after('button_link');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['button_text', 'button_link', 'color']);
        });
    }
};
