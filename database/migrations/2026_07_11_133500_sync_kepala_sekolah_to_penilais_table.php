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
        $kepalaSekolahUsers = \App\Models\User::with('kepalaSekolah')->where('role', 'kepala_sekolah')->get();
        foreach ($kepalaSekolahUsers as $kepsekUser) {
            \App\Models\Penilai::updateOrCreate(
                ['user_id' => $kepsekUser->id],
                [
                    'school_id' => $kepsekUser->school_id,
                    'nama' => $kepsekUser->name,
                    'nip' => $kepsekUser->kepalaSekolah ? $kepsekUser->kepalaSekolah->nip : null,
                    'pangkat_golongan_id' => $kepsekUser->kepalaSekolah ? $kepsekUser->kepalaSekolah->pangkat_golongan_id : null,
                    'jabatan' => 'Kepala Sekolah',
                    'instansi' => $kepsekUser->school ? $kepsekUser->school->nama : null,
                    'no_telepon' => $kepsekUser->kepalaSekolah ? $kepsekUser->kepalaSekolah->no_telepon : null,
                    'status' => $kepsekUser->is_active ? 'aktif' : 'nonaktif',
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Penilai::where('jabatan', 'Kepala Sekolah')->delete();
    }
};
