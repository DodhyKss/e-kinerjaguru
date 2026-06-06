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
            ],
            [
                'kode' => 'PROSES_PEMBELAJARAN',
                'nama' => 'Proses Pembelajaran',
                'deskripsi' => 'Dimensi yang menilai kualitas pelaksanaan pembelajaran, penilaian, dan pengelolaan kelas.',
                'urutan' => 2,
            ],
            [
                'kode' => 'FAKTOR_INTERNAL',
                'nama' => 'Influencing Factors - Faktor Internal',
                'deskripsi' => 'Faktor internal yang mempengaruhi kinerja guru: komitmen, motivasi, efikasi diri, kesehatan psikologis, dan resiliensi.',
                'urutan' => 3,
            ],
            [
                'kode' => 'FAKTOR_EKSTERNAL',
                'nama' => 'Influencing Factors - Faktor Eksternal',
                'deskripsi' => 'Faktor eksternal yang mempengaruhi kinerja guru: kepemimpinan, iklim sekolah, sarana prasarana, kolaborasi DUDI, dan sistem penghargaan.',
                'urutan' => 4,
            ],
        ];

        foreach ($dimensions as $dim) {
            Dimension::updateOrCreate(['kode' => $dim['kode']], $dim);
        }
    }
}
