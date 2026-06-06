<?php

namespace Database\Seeders;

use App\Models\EvaluationPeriod;
use App\Models\Guru;
use App\Models\Penilai;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create School
        $school = School::create([
            'nama' => 'SMK Negeri 1 Contoh',
            'npsn' => '10203040',
            'alamat' => 'Jl. Pendidikan No. 1, Kota Vokasi',
            'kabupaten' => 'Kota Vokasi',
            'provinsi' => 'Jawa Barat',
            'telepon' => '022-1234567',
            'email' => 'info@smkn1contoh.sch.id',
            'kepala_sekolah' => 'Dr. Budi Santoso, M.Pd.',
        ]);

        // 2. Create Admin User
        User::create([
            'name' => 'Administrator EKG',
            'email' => 'admin@ekg.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3. Create Kepala Sekolah User
        User::create([
            'name' => 'Dr. Budi Santoso, M.Pd.',
            'email' => 'kepsek@ekg.local',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
            'school_id' => $school->id,
        ]);

        // 4. Create Penilai (Asesor)
        $penilaiUser = User::create([
            'name' => 'Ahmad Hidayat, S.Pd., M.T.',
            'email' => 'penilai@ekg.local',
            'password' => Hash::make('password'),
            'role' => 'penilai',
            'school_id' => $school->id,
        ]);

        $penilai = Penilai::create([
            'user_id' => $penilaiUser->id,
            'school_id' => $school->id,
            'nama' => 'Ahmad Hidayat, S.Pd., M.T.',
            'nip' => '198001012005011001',
            'jabatan' => 'Asesor Kompetensi / Guru Senior',
            'instansi' => 'SMK Negeri 1 Contoh',
            'no_telepon' => '081234567890',
        ]);

        // 5. Create Guru
        $guruUser = User::create([
            'name' => 'Siti Aminah, S.Kom.',
            'email' => 'guru@ekg.local',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'school_id' => $school->id,
        ]);

        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'school_id' => $school->id,
            'nama' => 'Siti Aminah, S.Kom.',
            'nip' => '199002022015022002',
            'nuptk' => '1234567890123456',
            'mata_pelajaran' => 'Pemrograman Web dan Perangkat Bergerak',
            'kompetensi_keahlian' => 'Rekayasa Perangkat Lunak',
            'pangkat_golongan' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Guru Ahli Pertama',
            'jenis_kelamin' => 'P',
            'no_telepon' => '089876543210',
        ]);

        // 6. Create Evaluation Period
        EvaluationPeriod::create([
            'school_id' => $school->id,
            'nama' => 'Evaluasi Kinerja Guru Semester Ganjil 2025/2026',
            'tahun_ajaran' => '2025/2026',
            'semester' => 'ganjil',
            'tanggal_mulai' => '2025-11-01',
            'tanggal_selesai' => '2025-12-15',
            'status' => 'aktif',
        ]);
    }
}
