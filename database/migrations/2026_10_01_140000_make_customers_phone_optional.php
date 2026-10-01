<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * customers.phone was NOT NULL + UNIQUE, so only one customer could ever be saved without a phone
 * (as ""); the next customer without one crashed with "Duplicate entry '' for key
 * customers_phone_unique" right after logging in. A phone is optional for customers, and NULLs
 * don't clash in a unique index, so allow NULL and turn the empty values into NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers')) Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
        });

        if (Schema::hasTable('customers')) {
            DB::table('customers')->where('phone', '')->update(['phone' => null]);
        }
    }

    public function down(): void
    {
        // Leave the column nullable: forcing NOT NULL back would fail for customers without a phone.
    }
};
