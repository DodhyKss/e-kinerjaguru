<?php

namespace Database\Seeders;

use App\Models\Dimension;
use Illuminate\Database\Seeder;

class DimensionSeeder extends Seeder
{
    public function run(): void
    {
        $dimensions = [
            [
                'kode' => 'MUTU_GURU',
                'nama' => 'Mutu Guru',
                'deskripsi' => 'Dimensi yang menilai kualitas guru dalam perencanaan pembelajaran, pengembangan diri, dan kompetensi profesional.',
                'urutan' => 1,
                'urutan_romawi' => 'I',
            ],
            [
                'kode' => 'PROSES_PEMBELAJARAN',
                'nama' => 'Proses Pembelajaran',
                'deskripsi' => 'Dimensi yang menilai kualitas pelaksanaan pembelajaran, penilaian, dan pengelolaan kelas.',
                'urutan' => 2,
                'urutan_romawi' => 'II',
            ],
            [
                'kode' => 'INFLUENCING_FACTORS',
                'nama' => 'Influencing Factors',
                'deskripsi' => 'Faktor internal dan eksternal yang mempengaruhi kinerja guru: komitmen, motivasi, efikasi diri, kesehatan psikologis, resiliensi, kepemimpinan, iklim sekolah, sarana prasarana, kolaborasi DUDI, dan sistem penghargaan.',
                'urutan' => 3,
                'urutan_romawi' => 'III',
            ],
        ];

        foreach ($dimensions as $dim) {
            Dimension::updateOrCreate(['kode' => $dim['kode']], $dim);
        }
    }
}
