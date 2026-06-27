<?php

namespace Database\Seeders;

use App\Models\AchievementLevel;
use App\Models\AssessmentAspect;
use App\Models\Dimension;
use App\Models\Indicator;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMutuGuru();
        $this->seedProsePembelajaran();
        $this->seedFaktorInternal();
        $this->seedFaktorEksternal();

        $allIndicators = Indicator::join('dimensions', 'indicators.dimension_id', '=', 'dimensions.id')
            ->orderBy('dimensions.urutan')
            ->orderBy('indicators.urutan')
            ->select('indicators.*')
            ->get();

        foreach ($allIndicators as $index => $indicator) {
            Indicator::where('id', $indicator->id)->update(['urutan_keseluruhan' => $index + 1]);
        }
    }

    private function seedMutuGuru(): void
    {
        $dim = Dimension::where('kode', 'MUTU_GURU')->first();

        // 1. Perencanaan Pembelajaran
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'MG01'],
            [
                'nama' => 'Perencanaan Pembelajaran',
                'deskripsi' => 'Guru mengembangkan perencanaan pembelajaran yang berorientasi pada keaktifan, kreativitas, dan inovasi peserta didik melalui pengoptimalan sumber lingkungan dan integrasi teknologi informasi dan komunikasi sesuai dengan karakteristik konteks pembelajaran.',
                'urutan' => 1,
                'has_observasi' => false,
                'has_telaah_dokumen' => true,
                'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru mampu: (1) menyusun perencanaan pembelajaran yang memfasilitasi seluruh peserta didik belajar secara aktif, kreatif, inovatif, efektif, dan menyenangkan melalui kegiatan seperti proyek berbasis masalah, penelitian sederhana, pembelajaran berbasis produk, teaching factory, atau pembelajaran kontekstual yang relevan dengan kompetensi keahlian SMK; (2) mengoptimalkan pemanfaatan lingkungan sekolah, dunia kerja, dan lingkungan sekitar sebagai sumber belajar; (3) memanfaatkan TIK secara efektif melalui LMS, media digital, simulasi, video pembelajaran, atau aplikasi pembelajaran lainnya; dan (4) menjelaskan tahapan penyusunan perencanaan pembelajaran secara sistematis berdasarkan hasil refleksi dan evaluasi pembelajaran sebelumnya.',
            3 => 'Guru mampu: (1) menyusun perencanaan pembelajaran yang memfasilitasi peserta didik belajar aktif, kreatif, inovatif, dan menyenangkan melalui metode pembelajaran yang bervariasi; (2) memanfaatkan lingkungan sekitar sebagai sumber belajar; (3) menggunakan TIK untuk mendukung pembelajaran; dan (4) menjelaskan tahapan penyusunan perencanaan pembelajaran sesuai ketentuan yang berlaku.',
            2 => 'Guru mampu: (1) menyusun perencanaan pembelajaran yang memfasilitasi peserta didik belajar aktif dan kreatif, namun penerapan metode, pemanfaatan lingkungan, dan penggunaan TIK masih terbatas; dan (2) menjelaskan tahapan penyusunan perencanaan pembelajaran, tetapi belum sistematis dan belum berdasarkan hasil refleksi pembelajaran.',
            1 => 'Guru menyusun perencanaan pembelajaran yang belum memfasilitasi peserta didik belajar aktif, kreatif, dan inovatif, serta belum memanfaatkan lingkungan dan TIK secara optimal dalam mendukung pembelajaran.',
        ]);
        $this->createAspects($ind, [
            'telaah_dokumen' => [
                ['aspek' => 'Penyusunan perencanaan pembelajaran yang memfasilitasi pembelajaran aktif, kreatif, inovatif, efektif, dan menyenangkan', 'dokumen' => 'Modul Ajar/RPP'],
                ['aspek' => 'Pemanfaatan lingkungan sebagai sumber belajar', 'dokumen' => 'Modul Ajar/RPP'],
                ['aspek' => 'Pemanfaatan TIK dalam pembelajaran', 'dokumen' => 'Modul Ajar/RPP'],
                ['aspek' => 'Kesesuaian perencanaan pembelajaran dengan karakteristik kompetensi keahlian SMK', 'dokumen' => 'Modul Ajar/RPP'],
                ['aspek' => 'Pemanfaatan hasil refleksi/evaluasi pembelajaran sebelumnya dalam penyusunan perencanaan pembelajaran', 'dokumen' => 'Jurnal refleksi/catatan evaluasi'],
            ],
            'wawancara' => [
                ['aspek' => 'Tahapan dan prosedur penyusunan perencanaan pembelajaran'],
                ['aspek' => 'Pemanfaatan lingkungan sebagai sumber belajar'],
                ['aspek' => 'Pemanfaatan TIK dalam pembelajaran'],
                ['aspek' => 'Penggunaan metode pembelajaran aktif, kreatif, dan inovatif'],
                ['aspek' => 'Pemanfaatan hasil refleksi pembelajaran dalam penyusunan perencanaan berikutnya'],
            ],
        ]);

        // 2. Evaluasi Diri & Refleksi
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'MG02'],
            [
                'nama' => 'Evaluasi Diri dan Refleksi Pembelajaran',
                'deskripsi' => 'Guru menunjukkan upaya pengembangan profesional melalui kegiatan evaluasi diri, refleksi pembelajaran, dan peningkatan kompetensi yang dilakukan secara periodik untuk mendukung perbaikan kinerja.',
                'urutan' => 2,
                'has_observasi' => false,
                'has_telaah_dokumen' => true,
                'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru melakukan evaluasi dan refleksi diri secara sistematis melalui berbagai kegiatan seperti observasi pembelajaran, penilaian dari siswa, teman sejawat, supervisi kepala sekolah, rekaman audio/video, jurnal refleksi, dan analisis hasil belajar siswa. Hasil evaluasi dan refleksi didiskusikan, didiseminasikan, dan dimanfaatkan untuk pengembangan kompetensi serta perbaikan kinerja secara berkelanjutan.',
            3 => 'Guru melakukan evaluasi dan refleksi diri secara berkala melalui jurnal reflektif, catatan harian mengajar, atau hasil supervisi untuk memperbaiki pembelajaran dan mengembangkan kompetensi secara berkelanjutan.',
            2 => 'Guru melakukan evaluasi dan refleksi diri untuk perbaikan kinerja melalui catatan mengajar atau evaluasi sederhana, namun belum dilakukan secara sistematis dan belum dimanfaatkan secara optimal.',
            1 => 'Guru belum melakukan evaluasi dan refleksi diri secara konsisten atau masih memerlukan bantuan dalam melaksanakan evaluasi dan refleksi diri untuk perbaikan kinerja.',
        ]);
        $this->createAspects($ind, [
            'telaah_dokumen' => [
                ['aspek' => 'Evaluasi kinerja dan refleksi diri guru', 'dokumen' => 'Laporan evaluasi dan refleksi diri guru'],
                ['aspek' => 'Pelaksanaan refleksi diri secara berkala', 'dokumen' => 'Jurnal refleksi/catatan mengajar'],
                ['aspek' => 'Diskusi dan diseminasi hasil evaluasi dan refleksi diri', 'dokumen' => 'Notulen, daftar hadir, dokumentasi kegiatan'],
                ['aspek' => 'Pengembangan kompetensi berdasarkan hasil evaluasi dan refleksi diri', 'dokumen' => 'Sertifikat pelatihan/workshop/MGMP'],
                ['aspek' => 'Perbaikan mutu pembelajaran dan hasil belajar siswa berdasarkan hasil refleksi diri', 'dokumen' => 'Dokumen hasil evaluasi pembelajaran'],
            ],
            'wawancara' => [
                ['aspek' => 'Pelaksanaan evaluasi dan refleksi diri guru'],
                ['aspek' => 'Pemanfaatan jurnal refleksi atau catatan pembelajaran'],
                ['aspek' => 'Diskusi dan diseminasi hasil evaluasi dan refleksi diri'],
                ['aspek' => 'Pengembangan kompetensi guru berdasarkan hasil evaluasi dan refleksi diri'],
                ['aspek' => 'Dampak evaluasi dan refleksi diri terhadap peningkatan mutu pembelajaran'],
            ],
        ]);

        // 3. Pengembangan Profesi Berkelanjutan
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'MG03'],
            [
                'nama' => 'Pengembangan Profesi Berkelanjutan',
                'deskripsi' => 'Guru menunjukkan komitmen terhadap pengembangan profesi berkelanjutan melalui berbagai kegiatan yang mendukung peningkatan kapasitas pengetahuan, keterampilan, dan perluasan wawasan profesional.',
                'urutan' => 3,
                'has_observasi' => false,
                'has_telaah_dokumen' => true,
                'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru melakukan pengembangan profesi berkelanjutan atas inisiatif sendiri secara aktif dan konsisten melalui berbagai kegiatan seperti diskusi teman sejawat, KKG/MGMP, pelatihan, seminar, belajar daring, publikasi ilmiah, dan karya inovatif. Hasil pengembangan profesi berdampak nyata dan didiseminasikan.',
            3 => 'Guru melakukan pengembangan profesi berkelanjutan atas inisiatif sendiri melalui berbagai kegiatan seperti KKG/MGMP, seminar, pelatihan, belajar daring, publikasi ilmiah, atau karya inovatif yang berdampak terhadap peningkatan mutu pembelajaran.',
            2 => 'Guru melakukan pengembangan profesi berkelanjutan atas anjuran atau himbauan sekolah melalui kegiatan yang memberikan dampak terbatas terhadap peningkatan mutu pembelajaran.',
            1 => 'Guru melakukan pengembangan profesi dalam bentuk kegiatan yang masih terbatas dan lebih bersifat memenuhi kewajiban atau arahan sekolah. Hasil pengembangan profesi belum berdampak nyata.',
        ]);
        $this->createAspects($ind, [
            'telaah_dokumen' => [
                ['aspek' => 'Pengembangan profesi berkelanjutan melalui berbagai kegiatan', 'dokumen' => 'Sertifikat, laporan kegiatan, karya ilmiah'],
                ['aspek' => 'Dampak pengembangan profesi terhadap peningkatan mutu pembelajaran', 'dokumen' => 'Laporan evaluasi pembelajaran'],
                ['aspek' => 'Diseminasi hasil pengembangan profesi atau praktik baik', 'dokumen' => 'Makalah, video, artikel, dokumentasi kegiatan'],
                ['aspek' => 'Keikutsertaan aktif guru dalam komunitas belajar/profesi', 'dokumen' => 'Daftar hadir, notulen kegiatan'],
                ['aspek' => 'Pembuatan dan pemanfaatan karya inovatif dalam pembelajaran', 'dokumen' => 'Produk inovasi/media pembelajaran'],
            ],
            'wawancara' => [
                ['aspek' => 'Keikutsertaan guru dalam kegiatan pengembangan profesi berkelanjutan'],
                ['aspek' => 'Dampak pengembangan profesi terhadap peningkatan mutu pembelajaran'],
                ['aspek' => 'Keaktifan guru dalam kegiatan KKG/MGMP, seminar, pelatihan'],
                ['aspek' => 'Diseminasi hasil pengembangan profesi atau praktik baik'],
                ['aspek' => 'Pemanfaatan hasil pengembangan profesi dalam pembelajaran sehari-hari'],
            ],
        ]);

        // 4. Pengembangan Strategi & Media Pembelajaran
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'MG04'],
            [
                'nama' => 'Pengembangan Strategi dan Media Pembelajaran',
                'deskripsi' => 'Guru menunjukkan kemampuan dalam mengembangkan pendekatan pembelajaran melalui pemilihan strategi, model, metode, teknik, dan media yang variatif, kreatif, serta inovatif untuk meningkatkan efektivitas pembelajaran.',
                'urutan' => 4,
                'has_observasi' => true,
                'has_telaah_dokumen' => true,
                'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru mampu mengembangkan dan/atau memodifikasi strategi, model, metode, teknik, dan media pembelajaran yang kreatif dan inovatif. Inovasi pembelajaran yang dikembangkan telah menginspirasi teman sejawat dan/atau didiseminasikan.',
            3 => 'Guru mampu mengembangkan dan/atau memodifikasi strategi, model, metode, teknik, dan media pembelajaran kreatif dan inovatif yang mendorong peserta didik belajar aktif, efektif, dan menyenangkan.',
            2 => 'Guru mampu mengembangkan dan/atau memodifikasi strategi dan media pembelajaran, namun belum sepenuhnya relevan dengan tujuan pembelajaran atau belum dilakukan secara konsisten.',
            1 => 'Guru mengembangkan strategi dan media pembelajaran yang belum mampu mendorong keterlibatan aktif peserta didik dan belum mendukung tercapainya tujuan pembelajaran secara efektif.',
        ]);
        $this->createAspects($ind, [
            'observasi' => [
                ['aspek' => 'Pengembangan/modifikasi strategi, model, metode, teknik, dan media pembelajaran kreatif dan inovatif'],
                ['aspek' => 'Pengembangan pembelajaran yang mendorong siswa belajar aktif, efektif, dan menyenangkan'],
                ['aspek' => 'Implementasi pembelajaran inovatif sesuai tujuan pembelajaran'],
                ['aspek' => 'Pemanfaatan lingkungan dan sumber belajar kontekstual'],
                ['aspek' => 'Keterlibatan aktif peserta didik selama proses pembelajaran'],
            ],
            'telaah_dokumen' => [
                ['aspek' => 'Pengembangan profesi berkelanjutan melalui berbagai kegiatan', 'dokumen' => 'Sertifikat, laporan kegiatan, karya ilmiah'],
                ['aspek' => 'Dampak pengembangan profesi terhadap mutu pembelajaran', 'dokumen' => 'Laporan evaluasi pembelajaran'],
                ['aspek' => 'Diseminasi hasil pengembangan profesi', 'dokumen' => 'Makalah, video, artikel'],
                ['aspek' => 'Keikutsertaan aktif guru dalam komunitas belajar/profesi', 'dokumen' => 'Daftar hadir, notulen kegiatan'],
                ['aspek' => 'Pembuatan dan pemanfaatan karya inovatif', 'dokumen' => 'Produk inovasi/media pembelajaran'],
            ],
            'wawancara' => [
                ['aspek' => 'Pengembangan/modifikasi strategi dan media pembelajaran'],
                ['aspek' => 'Penggunaan media, teknologi informasi, dan sumber belajar'],
                ['aspek' => 'Pembelajaran yang mendorong siswa belajar aktif dan menyenangkan'],
                ['aspek' => 'Kesesuaian strategi dan media pembelajaran dengan tujuan pembelajaran'],
                ['aspek' => 'Diseminasi inovasi pembelajaran kepada teman sejawat'],
            ],
        ]);

        // 5. Pelatihan Asesor & Magang Industri
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'MG05'],
            [
                'nama' => 'Pelatihan Asesor Kompetensi dan Magang Industri',
                'deskripsi' => 'Guru melaksanakan pengembangan kompetensi profesional melalui keikutsertaan dalam pelatihan asesor kompetensi dan/atau kegiatan pemagangan di dunia kerja.',
                'urutan' => 5,
                'has_observasi' => true,
                'has_telaah_dokumen' => true,
                'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru SMK mengikuti pelatihan asesor kompetensi dan/atau magang di dunia kerja serta mampu menerapkan hasilnya secara optimal dalam pembelajaran teori dan praktik sesuai budaya dan iklim kerja industri.',
            3 => 'Guru SMK mengikuti pelatihan asesor kompetensi dan/atau magang di dunia kerja serta menerapkan hasilnya dalam pembelajaran di kelas maupun praktik.',
            2 => 'Guru SMK telah mengikuti pelatihan asesor kompetensi atau magang di dunia kerja, namun hasil pelatihan belum diterapkan secara optimal.',
            1 => 'Guru SMK belum mengikuti pelatihan asesor kompetensi maupun magang di dunia kerja.',
        ]);
        $this->createAspects($ind, [
            'observasi' => [
                ['aspek' => 'Pengimplementasian hasil pelatihan asesor atau pengalaman magang dalam pembelajaran'],
                ['aspek' => 'Penerapan pembelajaran berbasis teaching factory atau simulasi dunia kerja'],
                ['aspek' => 'Kesesuaian prosedur praktik dengan SOP industri'],
                ['aspek' => 'Pembiasaan disiplin kerja, keselamatan kerja, dan etos kerja industri'],
                ['aspek' => 'Keterlibatan peserta didik dalam praktik kerja berbasis standar industri'],
            ],
            'telaah_dokumen' => [
                ['aspek' => 'Pelatihan asesor atau pengalaman magang guru di dunia kerja', 'dokumen' => 'Sertifikat asesor/magang'],
                ['aspek' => 'Implementasi hasil pelatihan atau magang dalam pembelajaran', 'dokumen' => 'Perangkat pembelajaran praktik'],
                ['aspek' => 'Penerapan teaching factory atau pembelajaran berbasis industri', 'dokumen' => 'Modul ajar/RPP praktik'],
                ['aspek' => 'Penggunaan alat dan teknologi sesuai standar industri', 'dokumen' => 'SOP laboratorium/bengkel'],
                ['aspek' => 'Penilaian praktik berbasis kompetensi kerja industri', 'dokumen' => 'Instrumen asesmen praktik'],
            ],
            'wawancara' => [
                ['aspek' => 'Keikutsertaan guru dalam pelatihan asesor kompetensi atau magang industri'],
                ['aspek' => 'Implementasi hasil pelatihan atau magang dalam proses pembelajaran'],
                ['aspek' => 'Penerapan budaya kerja industri dalam pembelajaran praktik'],
                ['aspek' => 'Penggunaan job sheet, SOP, dan asesmen berbasis uji kompetensi'],
                ['aspek' => 'Dampak penerapan pengalaman industri terhadap kesiapan kerja siswa'],
            ],
        ]);

        // 6. Budaya Kerja Industri
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'MG06'],
            [
                'nama' => 'Penerapan Budaya Kerja Industri',
                'deskripsi' => 'Guru menerapkan budaya kerja industri dalam mendukung peningkatan mutu dan profesionalisme kerja.',
                'urutan' => 6,
                'has_observasi' => true,
                'has_telaah_dokumen' => true,
                'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru secara konsisten menerapkan budaya kerja industri dalam seluruh proses pembelajaran dan aktivitas profesional. Penerapan tersebut menjadi teladan bagi siswa dan rekan sejawat.',
            3 => 'Guru menerapkan budaya kerja industri dalam kegiatan pembelajaran dan pelaksanaan tugas profesional sehingga mendukung peningkatan mutu pembelajaran.',
            2 => 'Guru telah menerapkan beberapa unsur budaya kerja industri, namun belum dilakukan secara konsisten dan belum berdampak optimal.',
            1 => 'Guru belum menerapkan budaya kerja industri secara nyata dalam proses pembelajaran maupun pelaksanaan tugas profesional.',
        ]);
        $this->createAspects($ind, [
            'observasi' => [
                ['aspek' => 'Guru menerapkan disiplin kerja dalam pelaksanaan pembelajaran'],
                ['aspek' => 'Guru membiasakan budaya kerja industri seperti tanggung jawab, kerja sama, dan orientasi mutu'],
                ['aspek' => 'Guru menerapkan prosedur kerja dan keselamatan kerja dalam kegiatan praktik'],
                ['aspek' => 'Guru menunjukkan sikap profesional dalam berkomunikasi dan berinteraksi'],
                ['aspek' => 'Penerapan budaya kerja industri mendukung peningkatan kualitas pembelajaran'],
            ],
            'telaah_dokumen' => [
                ['aspek' => 'RPP memuat penerapan budaya kerja industri', 'dokumen' => 'RPP'],
                ['aspek' => 'Terdapat aturan/SOP pembelajaran yang mencerminkan budaya kerja industri', 'dokumen' => 'SOP/Tata Tertib Pembelajaran'],
                ['aspek' => 'Guru menggunakan instrumen penilaian sikap dan budaya kerja', 'dokumen' => 'Instrumen Penilaian Sikap/Kinerja'],
                ['aspek' => 'Dokumentasi kegiatan pembelajaran yang menerapkan budaya kerja industri', 'dokumen' => 'Dokumentasi Kegiatan Pembelajaran'],
                ['aspek' => 'Dokumen pengembangan profesional yang mendukung budaya kerja industri', 'dokumen' => 'Dokumen Pengembangan Profesional'],
            ],
            'wawancara' => [
                ['aspek' => 'Penerapan disiplin dan tanggung jawab dalam pembelajaran'],
                ['aspek' => 'Penerapan budaya kerja industri dalam kegiatan praktik maupun teori'],
                ['aspek' => 'Penerapan keselamatan kerja dan prosedur kerja selama pembelajaran'],
                ['aspek' => 'Pengaruh budaya kerja industri terhadap sikap dan kebiasaan kerja siswa'],
                ['aspek' => 'Dampak budaya kerja industri terhadap mutu pembelajaran dan profesionalisme kerja'],
            ],
        ]);
    }

    private function seedProsePembelajaran(): void
    {
        $dim = Dimension::where('kode', 'PROSES_PEMBELAJARAN')->first();

        // 7. Pembelajaran Aktif & HOTS
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'PP01'],
            [
                'nama' => 'Pembelajaran Aktif dan HOTS',
                'deskripsi' => 'Kegiatan Pembelajaran berlangsung secara aktif melalui keterlibatan seluruh peserta didik dan berorientasi pada penguatan keterampilan berpikir tingkat tinggi guna mendukung terciptanya proses belajar yang efektif.',
                'urutan' => 1,
                'has_observasi' => true,
                'has_telaah_dokumen' => true,
                'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru mampu melibatkan seluruh peserta didik secara aktif dan mengembangkan keterampilan berpikir tingkat tinggi (HOTS). Pembelajaran dilaksanakan melalui pengalaman konkret, bermakna, dan berdampak pada kemampuan pemecahan masalah.',
            3 => 'Guru memberi kesempatan kepada peserta didik untuk belajar secara aktif. Pembelajaran dilaksanakan melalui pengalaman konkret dan materi dikaitkan dengan kehidupan peserta didik.',
            2 => 'Guru memberi kesempatan belajar aktif, namun pengembangan keterampilan berpikir tingkat tinggi dan keterkaitan materi dengan kehidupan nyata belum optimal.',
            1 => 'Guru lebih dominan menjelaskan materi, sedangkan peserta didik lebih banyak mendengarkan, mencatat, dan mengerjakan tugas tanpa keterlibatan aktif yang bermakna.',
        ]);
        $this->createAspects($ind, [
            'observasi' => [
                ['aspek' => 'Pelibatan peserta didik secara aktif dalam pembelajaran'],
                ['aspek' => 'Pengembangan keterampilan berpikir tingkat tinggi (HOTS)'],
                ['aspek' => 'Pelaksanaan pembelajaran melalui pengalaman konkret'],
                ['aspek' => 'Penyajian materi yang bermakna dan kontekstual'],
                ['aspek' => 'Dampak pembelajaran terhadap kemampuan pemecahan masalah'],
            ],
            'telaah_dokumen' => [
                ['aspek' => 'Pelibatan peserta didik secara aktif dalam pembelajaran', 'dokumen' => 'Modul Ajar/RPP'],
                ['aspek' => 'Pengembangan keterampilan HOTS dalam kegiatan pembelajaran dan asesmen', 'dokumen' => 'Modul Ajar/RPP'],
                ['aspek' => 'Pelaksanaan pembelajaran melalui pengalaman konkret', 'dokumen' => 'Lembar kerja/praktikum'],
                ['aspek' => 'Penyajian materi yang kontekstual dan bermakna', 'dokumen' => 'Modul Ajar/RPP'],
                ['aspek' => 'Bukti penilaian kemampuan pemecahan masalah', 'dokumen' => 'Instrumen asesmen'],
            ],
            'wawancara' => [
                ['aspek' => 'Pelibatan peserta didik secara aktif dalam pembelajaran'],
                ['aspek' => 'Pengembangan keterampilan berpikir tingkat tinggi (HOTS)'],
                ['aspek' => 'Pelaksanaan pembelajaran melalui praktik dan pengalaman konkret'],
                ['aspek' => 'Penyajian materi yang bermakna dan relevan'],
                ['aspek' => 'Dampak pembelajaran terhadap kemampuan berpikir kritis siswa'],
            ],
        ]);

        // 8. Kesesuaian Kurikulum dengan Industri
        $ind = Indicator::updateOrCreate(
            ['dimension_id' => $dim->id, 'kode' => 'PP02'],
            [
                'nama' => 'Kesesuaian Kurikulum dengan Kebutuhan Industri',
                'deskripsi' => 'Adanya kesesuaian kurikulum sekolah dengan kebutuhan dan standar kerja industri.',
                'urutan' => 2,
                'has_observasi' => true, 'has_telaah_dokumen' => true, 'has_wawancara' => true,
            ]
        );
        $this->createLevels($ind, [
            4 => 'Guru mengimplementasikan kurikulum yang telah diselaraskan secara komprehensif dengan kebutuhan DUDI, melibatkan mitra industri dalam penyusunan, pelaksanaan, evaluasi pembelajaran.',
            3 => 'Guru menerapkan kurikulum yang telah disesuaikan dengan kebutuhan dan standar kerja industri dalam proses pembelajaran.',
            2 => 'Guru mulai menyesuaikan sebagian materi dengan kebutuhan industri, namun penerapannya belum konsisten.',
            1 => 'Guru melaksanakan pembelajaran berdasarkan kurikulum sekolah tanpa mengaitkan dengan kebutuhan dan standar dunia kerja/industri.',
        ]);
        $this->createAspects($ind, [
            'observasi' => [
                ['aspek' => 'Guru mengaitkan materi pembelajaran dengan kebutuhan pekerjaan dan dunia industri'],
                ['aspek' => 'Guru menerapkan prosedur, budaya kerja, dan standar operasional industri'],
                ['aspek' => 'Guru menggunakan perangkat/media yang relevan dengan praktik industri'],
                ['aspek' => 'Guru melaksanakan pembelajaran berbasis proyek/produk dari dunia industri'],
                ['aspek' => 'Guru melaksanakan penilaian yang mengacu pada standar kompetensi kerja industri'],
            ],
            'telaah_dokumen' => [
                ['aspek' => 'Dokumen kurikulum menunjukkan adanya sinkronisasi dengan kebutuhan industri', 'dokumen' => 'Dokumen Kurikulum Operasional Sekolah'],
                ['aspek' => 'Perangkat pembelajaran memuat kompetensi yang relevan dengan standar kerja industri', 'dokumen' => 'Modul Ajar/RPP/ATP'],
                ['aspek' => 'Materi praktik dan job sheet mengacu pada prosedur kerja industri', 'dokumen' => 'Job Sheet/Lembar Praktik'],
                ['aspek' => 'Terdapat dokumen kerja sama sekolah dengan DUDI', 'dokumen' => 'MoU/MoA dengan Industri'],
                ['aspek' => 'Instrumen penilaian menunjukkan penerapan standar kompetensi kerja industri', 'dokumen' => 'Instrumen dan Rubrik Penilaian'],
            ],
            'wawancara' => [
                ['aspek' => 'Keterlibatan industri dalam penyelarasan kurikulum sekolah'],
                ['aspek' => 'Penerapan materi pembelajaran yang sesuai kebutuhan dunia kerja'],
                ['aspek' => 'Penggunaan standar kerja industri dalam kegiatan praktik'],
                ['aspek' => 'Dampak pembelajaran terhadap kesiapan kerja dan kompetensi siswa'],
                ['aspek' => 'Pelaksanaan evaluasi dan pembaruan kurikulum sesuai perkembangan industri'],
            ],
        ]);

        // 9-16: Remaining Proses Pembelajaran indicators
        $ppIndicators = [
            ['kode' => 'PP03', 'nama' => 'Penilaian Pembelajaran', 'deskripsi' => 'Penilaian pembelajaran, baik pada proses maupun hasil belajar, dimanfaatkan untuk meningkatkan kualitas pembelajaran melalui pelaksanaan yang terencana dan sistematis.', 'urutan' => 3, 'obs' => true, 'td' => true, 'ww' => true,
                'levels' => [
                    4 => 'Guru melaksanakan penilaian proses dan hasil belajar secara sistemis, terencana, dan berkesinambungan dengan berbagai teknik penilaian. Hasil penilaian dianalisis dan dimanfaatkan sebagai dasar perbaikan.',
                    3 => 'Guru melaksanakan penilaian proses dan hasil belajar secara berkesinambungan dengan menggunakan berbagai teknik penilaian.',
                    2 => 'Guru melaksanakan penilaian sesuai tujuan pembelajaran, namun teknik penilaian masih terbatas dan hasil penilaian belum dimanfaatkan secara optimal.',
                    1 => 'Guru melaksanakan penilaian tanpa memperhatikan keterkaitan dengan tujuan pembelajaran serta belum menggunakan prosedur penilaian yang sistemis.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Penggunaan berbagai teknik/metode penilaian'],
                        ['aspek' => 'Pelaksanaan penilaian secara sistemis dan berkesinambungan'],
                        ['aspek' => 'Pemberian umpan balik terhadap hasil belajar peserta didik'],
                        ['aspek' => 'Pemanfaatan hasil penilaian untuk perbaikan proses pembelajaran'],
                        ['aspek' => 'Kesesuaian teknik penilaian dengan tujuan pembelajaran'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Penggunaan berbagai teknik/metode penilaian', 'dokumen' => 'Modul Ajar/RPP'],
                        ['aspek' => 'Pelaksanaan penilaian secara sistemis dan berkesinambungan', 'dokumen' => 'Kisi-kisi dan instrumen penilaian'],
                        ['aspek' => 'Pemanfaatan hasil penilaian untuk tindak lanjut pembelajaran', 'dokumen' => 'Program remedial/pengayaan'],
                        ['aspek' => 'Dokumentasi hasil penilaian peserta didik secara berkala', 'dokumen' => 'Rekap nilai/hasil asesmen'],
                        ['aspek' => 'Kesesuaian instrumen penilaian dengan tujuan pembelajaran', 'dokumen' => 'Instrumen asesmen'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Penggunaan berbagai teknik/metode penilaian dalam pembelajaran'],
                        ['aspek' => 'Penilaian terhadap aspek sikap, pengetahuan, dan keterampilan'],
                        ['aspek' => 'Pelaksanaan penilaian secara sistemis dan berkesinambungan'],
                        ['aspek' => 'Pemanfaatan hasil penilaian untuk tindak lanjut dan perbaikan pembelajaran'],
                        ['aspek' => 'Dampak penilaian terhadap peningkatan hasil belajar peserta didik'],
                    ],
                ],
            ],
            ['kode' => 'PP04', 'nama' => 'Remedial dan Pengayaan', 'deskripsi' => 'Kegiatan perbaikan pembelajaran maupun pengayaan diberikan kepada peserta didik yang membutuhkan untuk mendukung pencapaian kompetensi belajar.', 'urutan' => 4, 'obs' => false, 'td' => true, 'ww' => true,
                'levels' => [
                    4 => 'Guru melaksanakan program remedial dan/atau pengayaan secara sistematis, terstruktur, dan berkelanjutan berdasarkan hasil analisis. Program terbukti meningkatkan kompetensi secara signifikan.',
                    3 => 'Guru melaksanakan program remedial dan/atau pengayaan secara sistematis dan terstruktur dengan berbagai strategi sesuai kebutuhan peserta didik.',
                    2 => 'Guru melaksanakan program remedial atau pengayaan, namun pelaksanaannya masih terbatas pada beberapa kompetensi.',
                    1 => 'Guru melaksanakan program remedial atau pengayaan secara terbatas hanya melalui pemberian tes ulang atau tugas tambahan.',
                ],
                'aspects' => [
                    'telaah_dokumen' => [
                        ['aspek' => 'Pelaksanaan penilaian dan analisis pencapaian kompetensi', 'dokumen' => 'Daftar nilai dan analisis hasil belajar'],
                        ['aspek' => 'Penyusunan program remedial/pengayaan', 'dokumen' => 'Program remedial/pengayaan'],
                        ['aspek' => 'Pelaksanaan tindak lanjut hasil penilaian', 'dokumen' => 'Catatan tindak lanjut pembelajaran'],
                        ['aspek' => 'Penggunaan strategi pembelajaran yang berbeda sesuai kebutuhan', 'dokumen' => 'Modul ajar/RPP'],
                        ['aspek' => 'Bukti peningkatan hasil belajar setelah remedial/pengayaan', 'dokumen' => 'Hasil evaluasi/remedial'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Pelaksanaan penilaian dan analisis pencapaian kompetensi peserta didik'],
                        ['aspek' => 'Pelaksanaan program remedial dan/atau pengayaan'],
                        ['aspek' => 'Penggunaan strategi/metode remedial dan pengayaan yang bervariasi'],
                        ['aspek' => 'Tindak lanjut hasil remedial dan pengayaan'],
                        ['aspek' => 'Manfaat program remedial dan pengayaan terhadap peningkatan kompetensi'],
                    ],
                ],
            ],
            ['kode' => 'PP05', 'nama' => 'Suasana Kelas Positif dan Menyenangkan', 'deskripsi' => 'Kegiatan belajar berlangsung dengan keaktifan peserta didik yang tinggi disertai suasana kelas yang positif dan menyenangkan.', 'urutan' => 5, 'obs' => true, 'td' => false, 'ww' => true,
                'levels' => [
                    4 => 'Suasana pembelajaran berlangsung dinamis, interaktif, dan menyenangkan dengan keterlibatan aktif seluruh peserta didik. Pembelajaran berdampak pada tercapainya tujuan pembelajaran secara optimal.',
                    3 => 'Suasana pembelajaran berlangsung dinamis dengan adanya interaksi antarsiswa dan antara siswa dengan guru. Peserta didik antusias.',
                    2 => 'Suasana kelas berlangsung tertib dengan adanya interaksi timbal balik, namun keterlibatan aktif dan suasana menyenangkan belum optimal.',
                    1 => 'Suasana kelas berlangsung tertib, tetapi pembelajaran masih didominasi guru dengan komunikasi satu arah.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Interaksi antarsiswa dan antara siswa dengan guru'],
                        ['aspek' => 'Suasana pembelajaran yang menarik dan menyenangkan'],
                        ['aspek' => 'Antusiasme peserta didik dalam mengikuti pembelajaran'],
                        ['aspek' => 'Keterlibatan peserta didik dalam diskusi, tanya jawab, dan presentasi'],
                        ['aspek' => 'Dampak suasana pembelajaran terhadap ketercapaian tujuan pembelajaran'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Interaksi antarsiswa dan antara siswa dengan guru dalam proses pembelajaran'],
                        ['aspek' => 'Suasana pembelajaran yang menarik, menyenangkan, dan memotivasi'],
                        ['aspek' => 'Penggunaan strategi, metode, media yang mendukung keterlibatan aktif'],
                        ['aspek' => 'Antusiasme dan partisipasi peserta didik selama pembelajaran'],
                        ['aspek' => 'Dampak suasana pembelajaran terhadap pemahaman dan hasil belajar'],
                    ],
                ],
            ],
            ['kode' => 'PP06', 'nama' => 'Budaya Literasi', 'deskripsi' => 'Guru mengembangkan budaya literasi peserta didik melalui pembiasaan aktivitas membaca dan menulis secara berkelanjutan.', 'urutan' => 6, 'obs' => true, 'td' => true, 'ww' => true,
                'levels' => [
                    4 => 'Guru melakukan pembiasaan literasi secara terprogram dan berkelanjutan. Peserta didik menghasilkan karya literasi yang dipublikasikan di lingkungan sekolah maupun masyarakat.',
                    3 => 'Guru melakukan pembiasaan literasi secara terprogram sehingga terbentuk budaya membaca dan menulis serta menghasilkan karya-karya literasi.',
                    2 => 'Guru melakukan pembiasaan membaca dan menulis dalam pembelajaran, namun belum berdampak pada terbentuknya budaya literasi di luar kelas.',
                    1 => 'Guru belum melakukan pembiasaan membaca, menulis, berkomunikasi secara terprogram dan berkelanjutan.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Pembiasaan membaca dan menulis di kelas'],
                        ['aspek' => 'Pembiasaan membaca dan menulis di luar kelas'],
                        ['aspek' => 'Penyediaan fasilitas pemajangan karya tulis peserta didik'],
                        ['aspek' => 'Antusiasme peserta didik dalam kegiatan literasi'],
                        ['aspek' => 'Keterlibatan peserta didik dalam kegiatan literasi sekolah'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Program literasi membaca dan menulis yang berkelanjutan', 'dokumen' => 'Dokumen program literasi sekolah'],
                        ['aspek' => 'Publikasi dan lomba literasi peserta didik', 'dokumen' => 'Dokumen publikasi dan lomba literasi'],
                        ['aspek' => 'Integrasi kegiatan literasi dalam pembelajaran', 'dokumen' => 'Modul ajar/RPP'],
                        ['aspek' => 'Dokumentasi hasil karya literasi peserta didik', 'dokumen' => 'Portofolio karya peserta didik'],
                        ['aspek' => 'Program Gerakan Literasi Sekolah', 'dokumen' => 'Dokumen GLS'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Pembiasaan membaca dan menulis di kelas'],
                        ['aspek' => 'Pembiasaan membaca dan menulis di luar kelas'],
                        ['aspek' => 'Dorongan guru kepada peserta didik untuk menghasilkan karya literasi'],
                        ['aspek' => 'Penyediaan fasilitas pemajangan dan publikasi karya'],
                        ['aspek' => 'Dampak kegiatan literasi terhadap budaya membaca dan menulis'],
                    ],
                ],
            ],
            ['kode' => 'PP07', 'nama' => 'Kondisi Pembelajaran Kondusif', 'deskripsi' => 'Guru membangun kondisi pembelajaran yang kondusif melalui pemenuhan aspek keamanan, kenyamanan, kebersihan, serta dukungan terhadap kelancaran aktivitas belajar peserta didik.', 'urutan' => 7, 'obs' => true, 'td' => false, 'ww' => true,
                'levels' => [
                    4 => 'Guru mampu membangun lingkungan belajar yang aman, nyaman, bersih, dan mudah diakses dengan melibatkan peserta didik. Interaksi berlangsung harmonis, penuh saling menghargai.',
                    3 => 'Guru mampu menciptakan kondisi pembelajaran yang aman, nyaman, bersih, dan mendukung aktivitas belajar. Hubungan guru-siswa terjalin dengan baik.',
                    2 => 'Guru mulai menerapkan pengelolaan pembelajaran yang memperhatikan aspek keamanan, kenyamanan, kebersihan, namun belum konsisten.',
                    1 => 'Guru belum menunjukkan upaya yang memadai dalam menciptakan suasana pembelajaran yang aman, nyaman, bersih.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Pengelolaan ruang belajar yang mendukung keamanan, kebersihan, dan kelancaran aktivitas'],
                        ['aspek' => 'Terbangunnya hubungan sosial yang positif antarpeserta didik'],
                        ['aspek' => 'Keterlibatan peserta didik dalam menjaga kebersihan, keamanan lingkungan belajar'],
                        ['aspek' => 'Kondisi kelas yang tertib, nyaman, dan mendukung pembelajaran'],
                        ['aspek' => 'Kemudahan peserta didik dalam menggunakan fasilitas dan sumber belajar'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Pengelolaan kelas yang mendukung keamanan, kenyamanan, kebersihan'],
                        ['aspek' => 'Strategi pembelajaran yang membangun hubungan positif dan saling menghargai'],
                        ['aspek' => 'Hubungan sosial antara guru dan peserta didik maupun antarpeserta didik'],
                        ['aspek' => 'Keterlibatan peserta didik dalam menjaga kondisi kelas'],
                        ['aspek' => 'Pengaruh suasana belajar terhadap motivasi dan keterlibatan peserta didik'],
                    ],
                ],
            ],
            ['kode' => 'PP08', 'nama' => 'Pemanfaatan Sarana dan Prasarana', 'deskripsi' => 'Pemanfaatan sarana dan prasarana sekolah dilakukan secara optimal guna menunjang kelancaran dan kualitas proses pembelajaran.', 'urutan' => 8, 'obs' => true, 'td' => true, 'ww' => true,
                'levels' => [
                    4 => 'Guru mampu mengoptimalkan berbagai fasilitas dan infrastruktur pembelajaran, termasuk media hasil inovasi guru dan siswa. Pemanfaatan terbukti meningkatkan kualitas pembelajaran secara signifikan.',
                    3 => 'Guru memanfaatkan fasilitas yang tersedia di sekolah maupun di luar sekolah sebagai media dan sumber belajar yang efektif.',
                    2 => 'Guru telah menggunakan beberapa fasilitas dan sarana, namun penggunaannya masih terbatas.',
                    1 => 'Guru belum memanfaatkan fasilitas dan infrastruktur pembelajaran secara maksimal.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Guru menggunakan fasilitas sekolah sebagai media dan sumber belajar'],
                        ['aspek' => 'Guru memanfaatkan lingkungan sekolah maupun luar sekolah'],
                        ['aspek' => 'Guru menggunakan alat, media, atau teknologi pembelajaran secara efektif'],
                        ['aspek' => 'Siswa aktif dan antusias menggunakan sarana dan prasarana'],
                        ['aspek' => 'Pemanfaatan fasilitas mendukung tercapainya tujuan dan hasil belajar'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'RPP memuat penggunaan sarana dan prasarana', 'dokumen' => 'RPP'],
                        ['aspek' => 'RPP mencantumkan penggunaan media dan sumber belajar yang bervariasi', 'dokumen' => 'RPP'],
                        ['aspek' => 'RPP menunjukkan langkah penggunaan fasilitas pembelajaran', 'dokumen' => 'RPP'],
                        ['aspek' => 'Dokumen penggunaan fasilitas sekolah sebagai sumber belajar', 'dokumen' => 'Dokumen penggunaan sarana prasarana'],
                        ['aspek' => 'Dokumentasi kegiatan pembelajaran yang memanfaatkan sarana prasarana', 'dokumen' => 'Dokumen kegiatan pembelajaran'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Pemanfaatan fasilitas sekolah dalam mendukung proses pembelajaran'],
                        ['aspek' => 'Penggunaan media pembelajaran yang bervariasi'],
                        ['aspek' => 'Pemanfaatan lingkungan sekitar sebagai sumber belajar'],
                        ['aspek' => 'Dampak penggunaan sarana dan prasarana terhadap motivasi dan keaktifan'],
                        ['aspek' => 'Pengaruh penggunaan fasilitas pembelajaran terhadap hasil belajar'],
                    ],
                ],
            ],
            ['kode' => 'PP09', 'nama' => 'Teaching Factory', 'deskripsi' => 'Keberadaan unit produksi/business center/technopark/teaching factory dimanfaatkan oleh guru sebagai media pembelajaran kontekstual.', 'urutan' => 9, 'obs' => true, 'td' => true, 'ww' => true,
                'levels' => [
                    4 => 'Guru secara optimal memanfaatkan teaching factory sebagai media pembelajaran kontekstual berbasis industri. Pembelajaran mencerminkan budaya kerja industri dan berdampak pada kesiapan kerja siswa.',
                    3 => 'Guru memanfaatkan teaching factory sebagai media pembelajaran berbasis industri sehingga siswa memperoleh pengalaman belajar yang relevan.',
                    2 => 'Guru telah menggunakan teaching factory sebagai media pembelajaran, namun pemanfaatannya masih terbatas dan belum terintegrasi.',
                    1 => 'Guru belum memanfaatkan teaching factory secara efektif sebagai media pembelajaran berbasis industri.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Guru memanfaatkan teaching factory dalam proses pembelajaran'],
                        ['aspek' => 'Guru menerapkan budaya kerja industri dalam kegiatan pembelajaran'],
                        ['aspek' => 'Siswa terlibat aktif dalam kegiatan produksi atau layanan jasa'],
                        ['aspek' => 'Guru menggunakan peralatan dan sistem kerja sesuai standar industri'],
                        ['aspek' => 'Pembelajaran berbasis teaching factory meningkatkan kompetensi siswa'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'RPP memuat kegiatan pembelajaran berbasis teaching factory', 'dokumen' => 'RPP'],
                        ['aspek' => 'Dokumen pembelajaran menunjukkan penerapan budaya kerja industri', 'dokumen' => 'Modul/Job Sheet/SOP Praktik'],
                        ['aspek' => 'Terdapat jadwal/program pemanfaatan teaching factory', 'dokumen' => 'Program Teaching Factory'],
                        ['aspek' => 'Guru menggunakan instrumen penilaian praktik berbasis kompetensi', 'dokumen' => 'Lembar Penilaian Praktik'],
                        ['aspek' => 'Tersedia dokumentasi kegiatan pembelajaran berbasis produksi', 'dokumen' => 'Dokumentasi Kegiatan Pembelajaran'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Pemanfaatan teaching factory dalam pembelajaran'],
                        ['aspek' => 'Penerapan budaya kerja industri selama proses pembelajaran'],
                        ['aspek' => 'Keterlibatan siswa dalam kegiatan produksi atau layanan jasa'],
                        ['aspek' => 'Manfaat pembelajaran berbasis teaching factory'],
                        ['aspek' => 'Pengaruh pembelajaran berbasis industri terhadap kesiapan kerja'],
                    ],
                ],
            ],
            ['kode' => 'PP10', 'nama' => 'Praktik Kerja Lapangan (PKL)', 'deskripsi' => 'Guru memanfaatkan kegiatan Praktik Kerja Lapangan (PKL) sebagai bagian dari pembelajaran berbasis dunia kerja.', 'urutan' => 10, 'obs' => true, 'td' => true, 'ww' => true,
                'levels' => [
                    4 => 'Guru secara optimal memanfaatkan PKL sebagai bagian terpadu dari pembelajaran berbasis dunia kerja melalui perencanaan, pelaksanaan, monitoring, evaluasi, dan tindak lanjut. Hasil PKL diintegrasikan ke dalam proses pembelajaran.',
                    3 => 'Guru memanfaatkan PKL sebagai bagian dari pembelajaran berbasis dunia kerja dengan mengaitkan pengalaman kerja siswa ke dalam proses pembelajaran.',
                    2 => 'Guru telah memanfaatkan PKL dalam pembelajaran, namun masih terbatas pada pelaksanaan administratif.',
                    1 => 'Guru belum memanfaatkan PKL sebagai bagian dari pembelajaran berbasis dunia kerja secara efektif.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Guru mengintegrasikan pengalaman PKL siswa dalam pembelajaran'],
                        ['aspek' => 'Guru mengaitkan materi pembelajaran dengan kondisi dunia kerja'],
                        ['aspek' => 'Guru membahas budaya kerja industri berdasarkan pengalaman PKL'],
                        ['aspek' => 'Siswa aktif menyampaikan pengalaman PKL'],
                        ['aspek' => 'Pembelajaran berbasis pengalaman PKL meningkatkan kompetensi siswa'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'RPP memuat integrasi pengalaman PKL', 'dokumen' => 'RPP'],
                        ['aspek' => 'Terdapat program pembimbingan dan monitoring PKL', 'dokumen' => 'Dokumen Program PKL'],
                        ['aspek' => 'Guru menggunakan laporan PKL sebagai bahan pembelajaran', 'dokumen' => 'Laporan PKL Siswa'],
                        ['aspek' => 'Terdapat instrumen penilaian PKL', 'dokumen' => 'Instrumen Penilaian PKL'],
                        ['aspek' => 'Dokumentasi kegiatan monitoring dan evaluasi PKL', 'dokumen' => 'Dokumentasi Kegiatan PKL'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Pemanfaatan pengalaman PKL dalam proses pembelajaran'],
                        ['aspek' => 'Keterkaitan materi pembelajaran dengan dunia kerja'],
                        ['aspek' => 'Penerapan budaya kerja industri sebelum dan setelah PKL'],
                        ['aspek' => 'Manfaat PKL terhadap peningkatan kompetensi siswa'],
                        ['aspek' => 'Pengaruh PKL terhadap kesiapan kerja siswa'],
                    ],
                ],
            ],
        ];

        foreach ($ppIndicators as $data) {
            $ind = Indicator::updateOrCreate(
                ['dimension_id' => $dim->id, 'kode' => $data['kode']],
                [
                    'nama' => $data['nama'], 'deskripsi' => $data['deskripsi'], 'urutan' => $data['urutan'],
                    'has_observasi' => $data['obs'], 'has_telaah_dokumen' => $data['td'], 'has_wawancara' => $data['ww'],
                ]
            );
            $this->createLevels($ind, $data['levels']);
            $this->createAspects($ind, $data['aspects']);
        }
    }

    private function seedFaktorInternal(): void
    {
        $dim = Dimension::where('kode', 'INFLUENCING_FACTORS')->first();

        $indicators = [
            ['kode' => 'FI01', 'nama' => 'Komitmen Profesional', 'deskripsi' => 'Tingkat kesungguhan, konsistensi, dan tanggung jawab guru SMK dalam menjalankan tugas dan peran profesinya.', 'urutan' => 1,
                'levels' => [
                    4 => 'Guru menunjukkan komitmen profesional yang sangat tinggi dan konsisten. Guru secara proaktif mengembangkan kompetensi dan menjadi teladan serta penggerak bagi rekan sejawat.',
                    3 => 'Guru menunjukkan komitmen profesional yang baik dan stabil dalam melaksanakan tugas pokok dan fungsi sebagai pendidik.',
                    2 => 'Guru menunjukkan komitmen profesional yang cukup, namun masih bersifat administratif dan reaktif.',
                    1 => 'Guru menunjukkan komitmen profesional yang rendah dalam menjalankan tugas keguruan.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Konsistensi kehadiran, ketepatan waktu, dan kesiapan mengajar'],
                        ['aspek' => 'Sikap profesional dalam berinteraksi'],
                        ['aspek' => 'Kepatuhan terhadap tata tertib dan kode etik profesi guru'],
                        ['aspek' => 'Inisiatif dalam meningkatkan kualitas pembelajaran kejuruan'],
                        ['aspek' => 'Keterlibatan aktif dalam kegiatan sekolah'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Bukti pengembangan profesional berkelanjutan', 'dokumen' => 'Sertifikat pelatihan, workshop, diklat'],
                        ['aspek' => 'Keterlibatan dalam komunitas profesional', 'dokumen' => 'Notulen MGMP, SK tim, laporan kegiatan'],
                        ['aspek' => 'Kepatuhan administrasi akademik', 'dokumen' => 'Perangkat ajar, jurnal mengajar'],
                        ['aspek' => 'Pembaruan materi kejuruan sesuai DUDI', 'dokumen' => 'Modul ajar, bahan ajar, jobsheet'],
                        ['aspek' => 'Kontribusi pada pengembangan sekolah', 'dokumen' => 'Laporan inovasi, karya tulis, program'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Tanggung jawab guru dalam menjalankan tugas'],
                        ['aspek' => 'Konsistensi sikap profesional dan etika kerja'],
                        ['aspek' => 'Upaya pengembangan diri dan pembelajaran'],
                        ['aspek' => 'Peran guru dalam penguatan budaya kerja SMK'],
                        ['aspek' => 'Keteladanan dan integritas guru'],
                    ],
                ],
            ],
            ['kode' => 'FI02', 'nama' => 'Motivasi Kerja', 'deskripsi' => 'Dorongan internal dan eksternal yang memengaruhi kesungguhan, ketekunan, dan semangat guru SMK dalam melaksanakan tugas profesionalnya.', 'urutan' => 2,
                'levels' => [
                    4 => 'Guru menunjukkan motivasi kerja yang sangat tinggi, intrinsik, dan berkelanjutan. Guru bekerja dengan antusias dan penuh inisiatif.',
                    3 => 'Guru menunjukkan motivasi kerja yang baik dan stabil dalam menjalankan tugas utama.',
                    2 => 'Guru menunjukkan motivasi kerja yang cukup, namun belum konsisten. Pelaksanaan tugas lebih didorong oleh kewajiban administratif.',
                    1 => 'Guru menunjukkan motivasi kerja yang rendah. Guru sering bekerja sekadar memenuhi kewajiban minimal.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Antusiasme dan semangat guru dalam mengajar'],
                        ['aspek' => 'Ketekunan dan konsistensi menyelesaikan tugas'],
                        ['aspek' => 'Inisiatif mengambil peran atau tugas tambahan'],
                        ['aspek' => 'Respons guru terhadap tantangan dan perubahan'],
                        ['aspek' => 'Sikap positif dalam kegiatan sekolah/kejuruan'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Partisipasi dalam kegiatan sekolah', 'dokumen' => 'SK panitia, laporan kegiatan'],
                        ['aspek' => 'Konsistensi pelaksanaan tugas', 'dokumen' => 'Jurnal mengajar, laporan kerja'],
                        ['aspek' => 'Prestasi atau penghargaan kerja', 'dokumen' => 'Piagam, sertifikat'],
                        ['aspek' => 'Kegiatan pengembangan pembelajaran', 'dokumen' => 'Program inovasi, modul ajar'],
                        ['aspek' => 'Ketuntasan tugas dan tanggung jawab', 'dokumen' => 'Rekap kehadiran, laporan'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Semangat dan kesungguhan guru bekerja'],
                        ['aspek' => 'Kesiapan menghadapi beban dan tantangan kerja'],
                        ['aspek' => 'Inisiatif dan kemauan berprestasi'],
                        ['aspek' => 'Konsistensi kinerja sehari-hari'],
                        ['aspek' => 'Pengaruh motivasi guru terhadap siswa'],
                    ],
                ],
            ],
            ['kode' => 'FI03', 'nama' => 'Efikasi Diri', 'deskripsi' => 'Keyakinan guru SMK terhadap kemampuannya sendiri untuk merencanakan, melaksanakan, dan menyelesaikan tugas pembelajaran serta tanggung jawab profesional secara efektif.', 'urutan' => 3,
                'levels' => [
                    4 => 'Guru menunjukkan efikasi diri yang sangat tinggi dan kuat. Guru memiliki keyakinan mantap dan percaya diri mengambil keputusan pedagogik serta berani mencoba strategi baru.',
                    3 => 'Guru menunjukkan efikasi diri yang baik. Guru percaya pada kemampuannya dalam mengelola kelas dan menyelesaikan permasalahan umum.',
                    2 => 'Guru menunjukkan efikasi diri yang cukup, namun belum konsisten. Guru sering ragu dalam mengambil keputusan pembelajaran.',
                    1 => 'Guru menunjukkan efikasi diri yang rendah. Guru kurang percaya terhadap kemampuan dirinya.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Kepercayaan diri guru saat mengajar'],
                        ['aspek' => 'Kemandirian dalam mengambil keputusan pembelajaran'],
                        ['aspek' => 'Keberanian mencoba metode/strategi baru'],
                        ['aspek' => 'Ketahanan menghadapi kendala pembelajaran'],
                        ['aspek' => 'Sikap terhadap kegagalan dan umpan balik'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Perangkat ajar hasil pengembangan mandiri', 'dokumen' => 'Modul ajar, RPP'],
                        ['aspek' => 'Bukti inovasi pembelajaran', 'dokumen' => 'Laporan inovasi, media ajar'],
                        ['aspek' => 'Refleksi dan evaluasi diri', 'dokumen' => 'Jurnal refleksi, laporan evaluasi'],
                        ['aspek' => 'Partisipasi aktif dalam kegiatan profesional', 'dokumen' => 'Notulen MGMP, laporan kegiatan'],
                        ['aspek' => 'Tindak lanjut hasil supervisi', 'dokumen' => 'Dokumen perbaikan pembelajaran'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Kepercayaan guru terhadap kemampuan diri'],
                        ['aspek' => 'Kemandirian dalam menyelesaikan masalah'],
                        ['aspek' => 'Respons terhadap tantangan dan perubahan'],
                        ['aspek' => 'Keberanian mencoba hal baru'],
                        ['aspek' => 'Dampak sikap guru terhadap siswa'],
                    ],
                ],
            ],
            ['kode' => 'FI04', 'nama' => 'Kesehatan Psikologis (Burnout)', 'deskripsi' => 'Kondisi kesejahteraan mental guru SMK yang ditandai oleh kemampuan mengelola stres kerja, menjaga keseimbangan emosi, dan mempertahankan energi serta keterlibatan kerja.', 'urutan' => 4,
                'levels' => [
                    4 => 'Guru menunjukkan kesehatan psikologis yang sangat baik dan tingkat burnout sangat rendah. Guru mampu mengelola stres kerja secara efektif.',
                    3 => 'Guru menunjukkan kesehatan psikologis yang baik dengan gejala burnout minimal dan terkendali.',
                    2 => 'Guru menunjukkan kesehatan psikologis yang cukup, namun mulai memperlihatkan gejala burnout sedang.',
                    1 => 'Guru menunjukkan kesehatan psikologis yang rendah dengan tingkat burnout tinggi.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Stabilitas emosi dan pengendalian stres'],
                        ['aspek' => 'Energi dan keterlibatan dalam mengajar'],
                        ['aspek' => 'Respons terhadap tekanan dan konflik kerja'],
                        ['aspek' => 'Kualitas interaksi dengan siswa'],
                        ['aspek' => 'Konsistensi kinerja sehari-hari'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Kehadiran dan konsistensi kerja', 'dokumen' => 'Rekap absensi'],
                        ['aspek' => 'Penyelesaian tugas tepat waktu', 'dokumen' => 'Laporan kinerja, jurnal mengajar'],
                        ['aspek' => 'Permohonan cuti atau dispensasi', 'dokumen' => 'Surat izin/cuti'],
                        ['aspek' => 'Catatan supervisi atau pembinaan', 'dokumen' => 'Laporan supervisi'],
                        ['aspek' => 'Program dukungan kesejahteraan guru', 'dokumen' => 'Dokumen program sekolah'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Ketahanan guru menghadapi tekanan kerja'],
                        ['aspek' => 'Perubahan sikap dan emosi guru'],
                        ['aspek' => 'Dampak kondisi psikologis terhadap kinerja'],
                        ['aspek' => 'Strategi guru mengelola stres'],
                        ['aspek' => 'Persepsi siswa terhadap suasana pembelajaran'],
                    ],
                ],
            ],
            ['kode' => 'FI05', 'nama' => 'Resiliensi (Ketangguhan)', 'deskripsi' => 'Kemampuan guru SMK untuk bertahan, beradaptasi, dan bangkit kembali secara psikologis dan profesional ketika menghadapi tekanan, tantangan, kegagalan, atau perubahan.', 'urutan' => 5,
                'levels' => [
                    4 => 'Guru menunjukkan resiliensi yang sangat tinggi. Guru mampu bangkit dengan cepat dari tekanan dan menjadikannya sebagai peluang peningkatan kinerja.',
                    3 => 'Guru menunjukkan resiliensi yang baik. Guru mampu menyesuaikan diri dengan perubahan dan mempertahankan kinerja yang stabil.',
                    2 => 'Guru menunjukkan resiliensi yang cukup, namun belum konsisten. Guru membutuhkan waktu lama untuk pulih dari tekanan.',
                    1 => 'Guru menunjukkan resiliensi yang rendah. Guru mudah merasa tertekan dan sulit beradaptasi.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Ketekunan menghadapi kesulitan kerja'],
                        ['aspek' => 'Kemampuan beradaptasi terhadap perubahan'],
                        ['aspek' => 'Stabilitas emosi dalam situasi menekan'],
                        ['aspek' => 'Konsistensi kinerja pasca hambatan'],
                        ['aspek' => 'Sikap optimis dan pantang menyerah'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Tindak lanjut hasil evaluasi/supervisi', 'dokumen' => 'Dokumen perbaikan'],
                        ['aspek' => 'Catatan perubahan atau inovasi pembelajaran', 'dokumen' => 'Laporan inovasi'],
                        ['aspek' => 'Konsistensi pelaksanaan tugas', 'dokumen' => 'Jurnal mengajar'],
                        ['aspek' => 'Partisipasi dalam pelatihan adaptif', 'dokumen' => 'Sertifikat pelatihan'],
                        ['aspek' => 'Laporan pemecahan masalah pembelajaran', 'dokumen' => 'Laporan refleksi'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Ketangguhan guru menghadapi tekanan'],
                        ['aspek' => 'Respons terhadap perubahan kebijakan'],
                        ['aspek' => 'Cara guru bangkit dari kegagalan'],
                        ['aspek' => 'Konsistensi kinerja dalam situasi sulit'],
                        ['aspek' => 'Dampak sikap guru terhadap siswa'],
                    ],
                ],
            ],
        ];

        foreach ($indicators as $data) {
            $ind = Indicator::updateOrCreate(
                ['dimension_id' => $dim->id, 'kode' => $data['kode']],
                ['nama' => $data['nama'], 'deskripsi' => $data['deskripsi'], 'urutan' => $data['urutan'],
                 'has_observasi' => true, 'has_telaah_dokumen' => true, 'has_wawancara' => true]
            );
            $this->createLevels($ind, $data['levels']);
            $this->createAspects($ind, $data['aspects']);
        }
    }

    private function seedFaktorEksternal(): void
    {
        $dim = Dimension::where('kode', 'INFLUENCING_FACTORS')->first();

        $indicators = [
            ['kode' => 'FE01', 'nama' => 'Kepemimpinan Kepala Sekolah', 'deskripsi' => 'Kemampuan kepala sekolah dalam mengarahkan, membina, memfasilitasi, dan memotivasi guru untuk meningkatkan kinerja profesional.', 'urutan' => 6,
                'levels' => [
                    4 => 'Kepemimpinan menunjukkan arah visi yang jelas, inspiratif, dan berorientasi mutu. Supervisi akademik berkualitas. Dampak: kinerja guru tinggi dan inovatif.',
                    3 => 'Kepemimpinan berjalan efektif dan mendukung pelaksanaan tugas guru. Supervisi dan pembinaan rutin.',
                    2 => 'Kepemimpinan masih bersifat administratif dan belum konsisten. Supervisi belum optimal.',
                    1 => 'Kepemimpinan menunjukkan kinerja yang lemah, minim arahan, dan kurang memberikan dukungan.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Keterlibatan kepala sekolah dalam pembinaan guru'],
                        ['aspek' => 'Pelaksanaan supervisi akademik'],
                        ['aspek' => 'Kualitas komunikasi dengan guru'],
                        ['aspek' => 'Dukungan terhadap kegiatan pembelajaran'],
                        ['aspek' => 'Penciptaan budaya kerja yang positif'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Program supervisi akademik', 'dokumen' => 'Program supervisi'],
                        ['aspek' => 'Bukti pelaksanaan supervisi', 'dokumen' => 'Instrumen, laporan supervisi'],
                        ['aspek' => 'Program pengembangan guru', 'dokumen' => 'Rencana pelatihan, workshop'],
                        ['aspek' => 'Kebijakan sekolah terkait pembelajaran', 'dokumen' => 'SK, program kerja'],
                        ['aspek' => 'Dokumentasi kegiatan pembinaan guru', 'dokumen' => 'Laporan kegiatan'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Peran kepala sekolah dalam membina guru'],
                        ['aspek' => 'Kualitas komunikasi dan hubungan kerja'],
                        ['aspek' => 'Dukungan terhadap pembelajaran'],
                        ['aspek' => 'Keadilan dan transparansi kebijakan'],
                        ['aspek' => 'Dampak kepemimpinan terhadap kinerja guru'],
                    ],
                ],
            ],
            ['kode' => 'FE02', 'nama' => 'Lingkungan/Iklim Sekolah', 'deskripsi' => 'Kondisi sosial, emosional, dan profesional di lingkungan sekolah yang mencerminkan kualitas hubungan antarwarga sekolah, budaya kerja, komunikasi, kenyamanan, keamanan, serta dukungan terhadap pelaksanaan tugas guru.', 'urutan' => 7,
                'levels' => [
                    4 => 'Lingkungan/iklim sekolah sangat kondusif, kolaboratif, aman, dan berorientasi mutu. Guru merasa dihargai, didukung, dan termotivasi untuk berinovasi.',
                    3 => 'Lingkungan/iklim sekolah kondusif dan mendukung pelaksanaan tugas guru.',
                    2 => 'Lingkungan/iklim sekolah cukup kondusif, namun masih terdapat kendala dalam hubungan kerja atau komunikasi.',
                    1 => 'Lingkungan/iklim sekolah tidak kondusif, ditandai hubungan kerja kurang harmonis dan komunikasi tidak efektif.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Kualitas hubungan antar guru dan warga sekolah'],
                        ['aspek' => 'Suasana kerja (nyaman, aman, tertib)'],
                        ['aspek' => 'Budaya kerja (disiplin, kolaboratif, profesional)'],
                        ['aspek' => 'Pola komunikasi di lingkungan sekolah'],
                        ['aspek' => 'Dukungan lingkungan terhadap pembelajaran'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Program pengembangan budaya sekolah', 'dokumen' => 'Program kerja sekolah'],
                        ['aspek' => 'Kegiatan kolaboratif antar guru', 'dokumen' => 'Notulen rapat, MGMP'],
                        ['aspek' => 'Tata tertib dan kebijakan sekolah', 'dokumen' => 'Dokumen aturan sekolah'],
                        ['aspek' => 'Program peningkatan kenyamanan/keamanan', 'dokumen' => 'Laporan kegiatan'],
                        ['aspek' => 'Dokumentasi kegiatan sekolah', 'dokumen' => 'Foto/laporan kegiatan'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Kenyamanan dan keamanan lingkungan kerja'],
                        ['aspek' => 'Hubungan kerja antar warga sekolah'],
                        ['aspek' => 'Budaya kerja dan disiplin sekolah'],
                        ['aspek' => 'Kualitas komunikasi di sekolah'],
                        ['aspek' => 'Dampak lingkungan terhadap kinerja guru'],
                    ],
                ],
            ],
            ['kode' => 'FE03', 'nama' => 'Sarana dan Prasarana (Fisik & Digital)', 'deskripsi' => 'Seluruh fasilitas fisik dan non-fisik yang tersedia di sekolah untuk mendukung proses pembelajaran.', 'urutan' => 8,
                'levels' => [
                    4 => 'Sarana dan prasarana sangat lengkap, modern, relevan dengan standar DUDI, dan terkelola dengan baik. Guru mampu memanfaatkan secara maksimal.',
                    3 => 'Sarana dan prasarana cukup lengkap dan layak digunakan untuk mendukung pembelajaran.',
                    2 => 'Sarana dan prasarana terbatas atau kurang sesuai dengan kebutuhan pembelajaran kejuruan.',
                    1 => 'Sarana dan prasarana sangat terbatas, tidak layak, atau tidak tersedia sesuai kebutuhan.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Ketersediaan fasilitas pembelajaran (kelas, lab, bengkel)'],
                        ['aspek' => 'Kelayakan dan kondisi sarana'],
                        ['aspek' => 'Kesesuaian fasilitas dengan kebutuhan pembelajaran kejuruan'],
                        ['aspek' => 'Pemanfaatan sarana oleh guru dalam pembelajaran'],
                        ['aspek' => 'Aksesibilitas dan kemudahan penggunaan'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Data inventaris sarana prasarana', 'dokumen' => 'Buku inventaris'],
                        ['aspek' => 'Program pengadaan/pemeliharaan', 'dokumen' => 'Rencana kerja sekolah'],
                        ['aspek' => 'Laporan penggunaan fasilitas', 'dokumen' => 'Logbook laboratorium/bengkel'],
                        ['aspek' => 'Standar fasilitas sesuai DUDI', 'dokumen' => 'Dokumen sinkronisasi kurikulum'],
                        ['aspek' => 'Dokumentasi kondisi fasilitas', 'dokumen' => 'Foto/laporan kondisi'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Kecukupan sarana dan prasarana'],
                        ['aspek' => 'Kesesuaian fasilitas dengan kebutuhan pembelajaran'],
                        ['aspek' => 'Kemudahan akses dan penggunaan'],
                        ['aspek' => 'Dukungan fasilitas terhadap kinerja guru'],
                        ['aspek' => 'Kendala dalam penggunaan sarana'],
                    ],
                ],
            ],
            ['kode' => 'FE04', 'nama' => 'Kolaborasi dengan DUDI', 'deskripsi' => 'Bentuk kerja sama antara sekolah (SMK) dengan dunia usaha dan industri dalam rangka meningkatkan kualitas pembelajaran.', 'urutan' => 9,
                'levels' => [
                    4 => 'Kolaborasi dengan DUDI sangat kuat, strategis, dan berkelanjutan, serta terintegrasi dalam seluruh proses pembelajaran SMK.',
                    3 => 'Kolaborasi dengan DUDI berjalan dengan baik, ditandai adanya program PKL, kunjungan industri, atau kerja sama.',
                    2 => 'Kolaborasi dengan DUDI masih terbatas dan tidak berkelanjutan.',
                    1 => 'Kolaborasi dengan DUDI tidak ada atau sangat minim.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Keterlibatan industri dalam pembelajaran'],
                        ['aspek' => 'Penerapan standar industri dalam kelas/lab'],
                        ['aspek' => 'Aktivitas teaching factory atau sejenisnya'],
                        ['aspek' => 'Peran guru dalam kegiatan kolaborasi'],
                        ['aspek' => 'Relevansi pembelajaran dengan dunia kerja'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Dokumen kerja sama dengan DUDI', 'dokumen' => 'MoU/MoA'],
                        ['aspek' => 'Program PKL atau magang', 'dokumen' => 'Program kerja'],
                        ['aspek' => 'Sinkronisasi kurikulum dengan industri', 'dokumen' => 'Dokumen kurikulum'],
                        ['aspek' => 'Kegiatan pelatihan guru di industri', 'dokumen' => 'Sertifikat pelatihan'],
                        ['aspek' => 'Dokumentasi teaching factory', 'dokumen' => 'Laporan kegiatan'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Intensitas kerja sama dengan industri'],
                        ['aspek' => 'Manfaat kolaborasi bagi guru'],
                        ['aspek' => 'Dampak terhadap pembelajaran'],
                        ['aspek' => 'Keterlibatan guru dalam program DUDI'],
                        ['aspek' => 'Relevansi kompetensi dengan dunia kerja'],
                    ],
                ],
            ],
            ['kode' => 'FE05', 'nama' => 'Sistem Penghargaan dan Jalur Karier', 'deskripsi' => 'Mekanisme yang diterapkan oleh sekolah dalam memberikan imbalan, pengakuan, dan peluang pengembangan karir kepada guru.', 'urutan' => 10,
                'levels' => [
                    4 => 'Sistem penghargaan, kompensasi, dan jalur karir sangat jelas, adil, transparan, dan berbasis kinerja. Guru merasa dihargai dan termotivasi tinggi.',
                    3 => 'Sistem penghargaan dan kompensasi tersedia dan berjalan cukup baik dengan mekanisme yang relatif jelas.',
                    2 => 'Sistem penghargaan dan kompensasi masih terbatas dan kurang konsisten.',
                    1 => 'Sistem penghargaan, kompensasi, dan jalur karir tidak jelas atau tidak berjalan.',
                ],
                'aspects' => [
                    'observasi' => [
                        ['aspek' => 'Pemberian penghargaan kepada guru berprestasi'],
                        ['aspek' => 'Keterbukaan dalam sistem kompensasi'],
                        ['aspek' => 'Dampak penghargaan terhadap motivasi guru'],
                        ['aspek' => 'Kesempatan pengembangan karir'],
                        ['aspek' => 'Persepsi keadilan dalam pemberian penghargaan'],
                    ],
                    'telaah_dokumen' => [
                        ['aspek' => 'Kebijakan sistem penghargaan', 'dokumen' => 'SK, pedoman sekolah'],
                        ['aspek' => 'Data pemberian penghargaan', 'dokumen' => 'Daftar penerima penghargaan'],
                        ['aspek' => 'Sistem kompensasi/insentif', 'dokumen' => 'Dokumen keuangan'],
                        ['aspek' => 'Program pengembangan karir guru', 'dokumen' => 'Program pelatihan/promosi'],
                        ['aspek' => 'Bukti transparansi dan akuntabilitas', 'dokumen' => 'Laporan kegiatan'],
                    ],
                    'wawancara' => [
                        ['aspek' => 'Keadilan sistem penghargaan'],
                        ['aspek' => 'Transparansi kompensasi'],
                        ['aspek' => 'Motivasi kerja akibat penghargaan'],
                        ['aspek' => 'Kesempatan pengembangan karir'],
                        ['aspek' => 'Dampak terhadap kinerja guru'],
                    ],
                ],
            ],
        ];

        foreach ($indicators as $data) {
            $ind = Indicator::updateOrCreate(
                ['dimension_id' => $dim->id, 'kode' => $data['kode']],
                ['nama' => $data['nama'], 'deskripsi' => $data['deskripsi'], 'urutan' => $data['urutan'],
                 'has_observasi' => true, 'has_telaah_dokumen' => true, 'has_wawancara' => true]
            );
            $this->createLevels($ind, $data['levels']);
            $this->createAspects($ind, $data['aspects']);
        }
    }

    private function createLevels(Indicator $indicator, array $levels): void
    {
        foreach ($levels as $level => $deskripsi) {
            AchievementLevel::updateOrCreate(
                ['indicator_id' => $indicator->id, 'level' => $level],
                ['deskripsi' => $deskripsi]
            );
        }
    }

    private function createAspects(Indicator $indicator, array $methodAspects): void
    {
        foreach ($methodAspects as $metode => $aspects) {
            foreach ($aspects as $idx => $aspect) {
                AssessmentAspect::updateOrCreate(
                    ['indicator_id' => $indicator->id, 'metode' => $metode, 'nomor' => $idx + 1],
                    [
                        'aspek' => $aspect['aspek'],
                        'nama_dokumen' => $aspect['dokumen'] ?? null,
                    ]
                );
            }
        }
    }
}
