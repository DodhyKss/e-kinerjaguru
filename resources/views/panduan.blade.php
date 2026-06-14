<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Lengkap - E-Kinerja Guru SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full font-sans antialiased text-slate-900 flex flex-col py-10 px-4 md:px-8">
    <div class="max-w-6xl mx-auto w-full">
        <!-- Header & Back Button -->
        <div class="mb-8">
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali ke Halaman Login
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100/50 overflow-hidden border border-slate-100">
            <!-- Hero Section -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 px-8 py-16 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="relative z-10">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-md text-white mb-6 shadow-inner border border-white/20">
                        <i data-lucide="book-open" class="h-10 w-10"></i>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Buku Panduan Lengkap</h1>
                    <p class="text-lg text-indigo-100 max-w-2xl mx-auto">Sistem Informasi Pengelolaan & Evaluasi Kinerja Guru (E-Kinerja) SMK. Pelajari alur kerja dan fungsi detail setiap menu di dalam aplikasi.</p>
                </div>
            </div>

            <div class="p-8 md:p-12">
                
                <!-- ALUR APLIKASI -->
                <div class="mb-16">
                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 pb-4">
                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                            <i data-lucide="workflow" class="w-6 h-6"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">Alur Sistem (Workflow)</h2>
                    </div>

                    <div class="relative">
                        <!-- Connecting Line -->
                        <div class="absolute top-8 left-[2.25rem] bottom-8 w-1 bg-indigo-100 hidden md:block z-0"></div>
                        
                        <div class="space-y-8 relative z-10">
                            <!-- Step 1 -->
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-full bg-indigo-600 text-white font-bold text-xl border-4 border-white shadow-md z-10">1</div>
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">Persiapan Master Data (Oleh Admin)</h3>
                                    <p class="text-sm text-slate-600">Admin menyiapkan seluruh infrastruktur awal: mendaftarkan Sekolah, mendaftarkan Guru dan Asesor (serta mengaitkan guru dengan sekolahnya), mengatur Instrumen Penilaian, dan membuka Periode Evaluasi yang aktif.</p>
                                </div>
                            </div>
                            <!-- Step 2 -->
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-full bg-blue-500 text-white font-bold text-xl border-4 border-white shadow-md z-10">2</div>
                                <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">Pelaksanaan Penilaian (Oleh Asesor/Penilai)</h3>
                                    <p class="text-sm text-slate-600">Asesor login, memilih menu Data Evaluasi, lalu menekan tombol "Mulai Evaluasi" pada guru yang ingin dinilai. Asesor mengisi nilai (skala 1-4) di setiap indikator berdasarkan bukti yang diperoleh dari telaah dokumen, observasi, dan wawancara. Selain itu, Penilai juga menyusun dan menuliskan rekomendasi lanjutan bagi guru bersangkutan. Setelah rampung, Asesor menyelesaikan status draf menjadi "Completed".</p>
                                </div>
                            </div>
                            <!-- Step 3 -->
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-full bg-emerald-500 text-white font-bold text-xl border-4 border-white shadow-md z-10">3</div>
                                <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100 flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">Validasi & Persetujuan (Oleh Kepala Sekolah)</h3>
                                    <p class="text-sm text-slate-600">Kepala Sekolah login, mengecek daftar evaluasi guru di sekolahnya yang berstatus "Completed". Kepala Sekolah memverifikasi nilai tersebut dan menekan tombol "Setujui (Approve)" agar nilai tersebut menjadi final dan sah.</p>
                                </div>
                            </div>
                            <!-- Step 4 -->
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-full bg-amber-500 text-white font-bold text-xl border-4 border-white shadow-md z-10">4</div>
                                <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100 flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">Transparansi & Laporan (Semua Pihak)</h3>
                                    <p class="text-sm text-slate-600">Guru yang dinilai dapat langsung melihat Rapor Kinerjanya dan mencetak PDF. Kepala Sekolah dan Admin dapat memantau Ranking Guru (siapa yang terbaik), melihat Grafik Kinerja sekolah, dan mencetak Laporan Buku Induk (Rekapitulasi).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RINCIAN MENU -->
                <div>
                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 pb-4">
                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                            <i data-lucide="list-tree" class="w-6 h-6"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">Rincian Menu Berdasarkan Peran (Role)</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- ADMIN -->
                        <div class="border border-slate-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <i data-lucide="shield" class="w-8 h-8 text-indigo-600"></i>
                                <h3 class="text-xl font-bold text-slate-900">1. Administrator (Admin)</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Persiapan Sistem & Dashboard</h4>
                                    <p class="text-xs text-slate-500 mt-1">Admin merupakan pemegang kendali utama aplikasi. Pada Dashboard, Admin memantau rekap data seluruh sistem (jumlah guru, sekolah, dan status evaluasi secara real-time).</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Manajemen Master Data Induk</h4>
                                    <p class="text-xs text-slate-500 mt-1">Langkah pertama yang wajib dilakukan adalah mengisi atribut dasar seperti Provinsi, Kabupaten, Kelompok Mata Pelajaran, Mata Pelajaran, Kompetensi Keahlian, Pangkat/Golongan, hingga Jabatan Fungsional agar identitas pegawai terdata secara rapi.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Registrasi Sekolah, Guru & Evaluator</h4>
                                    <p class="text-xs text-slate-500 mt-1">Admin membuatkan akun bagi setiap Sekolah, Guru, Kepala Sekolah, dan Asesor/Evaluator. Saat pembuatan, Admin menentukan hak akses (Role) dan memetakan penempatan unit kerjanya agar akun tersebut tertaut secara struktural.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Pengaturan Periode & Instrumen Penilaian</h4>
                                    <p class="text-xs text-slate-500 mt-1">Admin merancang siklus evaluasi melalui menu Periode (misal: "Tahun Ajaran 2024 Genap"). Selain itu, Admin menyusun borang penilaian secara hierarkis (Dimensi > Aspek > Indikator) yang nantinya akan digunakan oleh Evaluator untuk menilai Guru.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Pemantauan & Cetak Rekapitulasi Global</h4>
                                    <p class="text-xs text-slate-500 mt-1">Admin memiliki otoritas memantau seluruh Laporan Evaluasi, Grafik Kinerja lintas periode, dan Ranking Guru terbaik. Admin juga bertugas mencetak Rekapitulasi Kinerja (Buku Induk) lengkap beserta catatan rekomendasi dari seluruh sekolah.</p>
                                </div>
                            </div>
                        </div>

                        <!-- KEPALA SEKOLAH -->
                        <div class="border border-slate-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <i data-lucide="building" class="w-8 h-8 text-emerald-600"></i>
                                <h3 class="text-xl font-bold text-slate-900">2. Kepala Sekolah</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Pemantauan Kinerja Internal</h4>
                                    <p class="text-xs text-slate-500 mt-1">Kepala Sekolah bertindak sebagai penanggung jawab mutu di tingkat satuan pendidikan. Melalui Dashboard dan Grafik Kinerja, beliau memantau tren perolehan nilai sekolahnya dari periode ke periode dalam bentuk visual (Pie/Bar/Line Chart).</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Supervisi Asesor & Guru</h4>
                                    <p class="text-xs text-slate-500 mt-1">Beliau dapat melihat seluruh guru dan asesor yang ditugaskan di sekolahnya (mode baca/read-only). Hal ini memudahkan pengawasan agar setiap guru telah memiliki penugasan evaluasi.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Verifikasi & Pengesahan Dokumen</h4>
                                    <p class="text-xs text-slate-500 mt-1">Menu Data Evaluasi adalah pintu terakhir sebelum nilai disahkan. Kepala Sekolah bertugas membaca detail kuesioner dari Evaluator, memberikan Catatan/Feedback tambahan (opsional), lalu mengklik tombol "Setujui" agar hasil menjadi sah dan final.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Pemetaan Guru Berprestasi (Ranking)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Sistem otomatis menyajikan Peringkat (Ranking) performa guru terbaik di sekolahnya berdasarkan Nilai Akhir untuk mempermudah kepala sekolah dalam memberikan referensi penghargaan, promosi, atau tunjangan kinerja.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Otorisasi Laporan (Rekapitulasi)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Mencetak Rekapitulasi (Buku Induk) satu lembar PDF yang memuat rekap nilai serta rekomendasi seluruh guru. Lembar ini dicetak untuk ditandatangani secara basah sebagai bukti akreditasi dan laporan ke dinas.</p>
                                </div>
                            </div>
                        </div>

                        <!-- EVALUATOR -->
                        <div class="border border-slate-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <i data-lucide="user-check" class="w-8 h-8 text-blue-600"></i>
                                <h3 class="text-xl font-bold text-slate-900">3. Asesor / Evaluator</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Manajemen Penugasan</h4>
                                    <p class="text-xs text-slate-500 mt-1">Melalui Dashboard, Evaluator dapat melacak progres tugasnya secara presisi (seperti jumlah guru yang belum dinilai, sedang dalam proses draf, atau sudah dikirim ke Kepala Sekolah).</p>
                                </div>
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Pelaksanaan Observasi & Telaah Borang</h4>
                                    <p class="text-xs text-slate-500 mt-1">Di menu Data Evaluasi, Evaluator memberikan bobot nilai (skala 1-4) beserta kesimpulan untuk tiap indikator. Acuan utamanya adalah dari bukti otentik seperti: Observasi Kelas, Telaah Dokumen bukti RPP/Silabus yang diunggah guru, serta sesi Wawancara langsung.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Penyusunan Rekomendasi Khusus</h4>
                                    <p class="text-xs text-slate-500 mt-1">Setelah menuntaskan seluruh butir penilaian, Evaluator wajib merumuskan catatan "Rekomendasi Lanjutan" (tindak lanjut perbaikan) yang akan menjadi pedoman pembinaan guru bersangkutan di masa depan.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Draf Terpadu & Riwayat Penilaian</h4>
                                    <p class="text-xs text-slate-500 mt-1">Proses penilaian bisa dilakukan bertahap menggunakan status "Draft". Setelah semua lengkap, klik Submit (Status berubah menjadi Completed). Evaluator juga dapat melihat riwayat lengkap laporannya untuk keperluan rekam jejak.</p>
                                </div>
                            </div>
                        </div>

                        <!-- GURU -->
                        <div class="border border-slate-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <i data-lucide="users" class="w-8 h-8 text-amber-600"></i>
                                <h3 class="text-xl font-bold text-slate-900">4. Guru (Target Evaluasi)</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="pl-4 border-l-2 border-amber-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Refleksi Kinerja (Dashboard)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Begitu masuk ke aplikasi, guru langsung disuguhkan dengan status penilaian berjalannya dan nilai akhir rata-rata IPK yang telah diverifikasi Kepala Sekolah.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-amber-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Upload Bukti Kinerja (Lintas Indikator)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Sebagai pendukung penilaian "Telaah Dokumen", guru dapat mengunggah berbagai berkas (RPP, Sertifikat, Presensi). Melalui fitur <b>Upload General</b>, efisiensi ditingkatkan di mana 1 file bukti saja dapat dipetakan langsung ke beberapa aspek penilaian lintas indikator sekaligus.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-amber-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Monitoring Hasil & Rapor Detail</h4>
                                    <p class="text-xs text-slate-500 mt-1">Guru memantau rincian poin tiap indikator secara detail dan transparan untuk melihat kekurangan/kelebihan performanya (tersedia begitu draf penilaian selesai).</p>
                                </div>
                                <div class="pl-4 border-l-2 border-amber-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Pencetakan Laporan Resmi</h4>
                                    <p class="text-xs text-slate-500 mt-1">Satu klik untuk mengunduh Laporan (Rapor) format PDF ber-Kop Surat resmi sekolah. Dokumen fisik ini memuat rincian kompetensi, kesimpulan, hingga tanda tangan pihak berwenang untuk kebutuhan kenaikan jenjang karir.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        
        <div class="mt-10 mb-10 text-center text-sm text-slate-500 font-medium">
            Sistem Evaluasi Kinerja Guru (E-Kinerja SMK) &copy; {{ date('Y') }}
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
