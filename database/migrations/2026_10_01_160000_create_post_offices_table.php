<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * India Post directory: every post office with its PIN code, district and state (36 states/UTs,
 * ~155k offices, ~19k PIN codes). Used for the State → District → PIN code → Area dropdowns in
 * merchant sign-up, and to check that an entered PIN belongs to the chosen state and district.
 *
 * Data: India Post "All India Pincode Directory" (2023 snapshot, via bharat-post-dir, MIT),
 * cleaned (title case, current state names, Ladakh split out). Source file:
 * database/data/india_post_offices.csv.gz
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('post_offices')) {
            Schema::create('post_offices', function (Blueprint $table) {
                $table->id();
                $table->string('state', 60);
                $table->string('district', 80);
                $table->string('area', 120);
                $table->char('pincode', 6);
                $table->string('office_type', 4)->nullable();
                $table->string('delivery', 20)->nullable();
                $table->index(['state', 'district']);
                $table->index('pincode');
            });
        }

        if (DB::table('post_offices')->exists()) {
            return;
        }

        $file = database_path('data/india_post_offices.csv.gz');
        $handle = gzopen($file, 'r');
        if ($handle === false) {
            throw new RuntimeException("Cannot read $file");
        }

        $header = str_getcsv(trim(gzgets($handle)), ',', '"', '');
        $batch = [];
        while (($line = gzgets($handle)) !== false) {
            if (trim($line) === '') {
                continue;
            }
            $row = array_combine($header, str_getcsv(rtrim($line, "\r\n"), ',', '"', ''));
            $batch[] = [
                'state' => $row['state'],
                'district' => $row['district'],
                'area' => mb_substr($row['area'], 0, 120),
                'pincode' => $row['pincode'],
                'office_type' => $row['office_type'] ?: null,
                'delivery' => $row['delivery'] ?: null,
            ];
            if (count($batch) === 1000) {
                DB::table('post_offices')->insert($batch);
                $batch = [];
            }
        }
        if ($batch) {
            DB::table('post_offices')->insert($batch);
        }
        gzclose($handle);
    }

    public function down(): void
    {
        Schema::dropIfExists('post_offices');
    }
};
