<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Migrate any existing string values in 'provinsi' and 'kabupaten' to foreign keys before dropping
        $schools = DB::table('schools')->get();
        foreach ($schools as $school) {
            $update = [];

            if (empty($school->provinsi_id) && !empty($school->provinsi)) {
                $prov = DB::table('provinsis')->where('nama', $school->provinsi)->first();
                if (!$prov) {
                    $provId = DB::table('provinsis')->insertGetId([
                        'nama' => $school->provinsi,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $provId = $prov->id;
                }
                $update['provinsi_id'] = $provId;
            }

            if (empty($school->kabupaten_id) && !empty($school->kabupaten)) {
                $provId = $update['provinsi_id'] ?? $school->provinsi_id;
                if (!$provId) {
                    $prov = DB::table('provinsis')->first();
                    if ($prov) {
                        $provId = $prov->id;
                    } else {
                        $provId = DB::table('provinsis')->insertGetId([
                            'nama' => 'Jawa Barat',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    $update['provinsi_id'] = $provId;
                }

                $kab = DB::table('kabupatens')
                    ->where('nama', $school->kabupaten)
                    ->where('provinsi_id', $provId)
                    ->first();

                if (!$kab) {
                    $kabId = DB::table('kabupatens')->insertGetId([
                        'provinsi_id' => $provId,
                        'nama' => $school->kabupaten,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $kabId = $kab->id;
                }
                $update['kabupaten_id'] = $kabId;
            }

            if (!empty($update)) {
                DB::table('schools')->where('id', $school->id)->update($update);
            }
        }

        // 2. Drop the redundant string columns that shadow the relationship methods
        if (Schema::hasColumn('schools', 'provinsi') || Schema::hasColumn('schools', 'kabupaten')) {
            Schema::table('schools', function (Blueprint $table) {
                if (Schema::hasColumn('schools', 'provinsi')) {
                    $table->dropColumn('provinsi');
                }
                if (Schema::hasColumn('schools', 'kabupaten')) {
                    $table->dropColumn('kabupaten');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'provinsi')) {
                $table->string('provinsi')->nullable();
            }
            if (!Schema::hasColumn('schools', 'kabupaten')) {
                $table->string('kabupaten')->nullable();
            }
        });

        // Restore string values from foreign keys
        $schools = DB::table('schools')->get();
        foreach ($schools as $school) {
            $update = [];
            if (!empty($school->provinsi_id)) {
                $prov = DB::table('provinsis')->where('id', $school->provinsi_id)->first();
                if ($prov) {
                    $update['provinsi'] = $prov->nama;
                }
            }
            if (!empty($school->kabupaten_id)) {
                $kab = DB::table('kabupatens')->where('id', $school->kabupaten_id)->first();
                if ($kab) {
                    $update['kabupaten'] = $kab->nama;
                }
            }
            if (!empty($update)) {
                DB::table('schools')->where('id', $school->id)->update($update);
            }
        }
    }
};
