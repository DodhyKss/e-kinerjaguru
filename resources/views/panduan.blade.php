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
                                    <p class="text-sm text-slate-600">Asesor login, memilih menu Data Evaluasi, lalu menekan tombol "Mulai Evaluasi" pada guru yang ingin dinilai. Asesor mengisi nilai (skala 1-4) di setiap indikator berdasarkan Observasi dan Dokumen. Setelah rampung, Asesor menyelesaikan status draf menjadi "Completed".</p>
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
                                    <h4 class="font-bold text-slate-800 text-sm">Dashboard</h4>
                                    <p class="text-xs text-slate-500 mt-1">Halaman muka berisi ringkasan statistik (jumlah guru, sekolah, dan status evaluasi berjalan).</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Master Wilayah & Atribut Guru</h4>
                                    <p class="text-xs text-slate-500 mt-1">Menu untuk menambah/mengedit data fundamental seperti: Provinsi, Kabupaten, Mata Pelajaran, Kompetensi Keahlian, Pangkat/Golongan, dan Jabatan Fungsional.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Data Sekolah</h4>
                                    <p class="text-xs text-slate-500 mt-1">Manajemen unit kerja/sekolah. Menentukan nama, alamat, serta kabupaten sekolah terkait.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Data Guru & Penilai</h4>
                                    <p class="text-xs text-slate-500 mt-1">Mendaftarkan akun Guru dan akun Asesor. Saat menambah, Admin mengatur penempatan unit kerjanya agar akun tersebut tertaut ke sekolah tertentu.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Periode Evaluasi & Master Instrumen</h4>
                                    <p class="text-xs text-slate-500 mt-1">Membuat periode penilaian (contoh: "Tahun Ajaran 2024 Genap"). Serta membuat susunan pertanyaan kuesioner penilaian yang terdiri dari Dimensi -> Aspek -> Indikator.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Laporan, Grafik & Rekapitulasi</h4>
                                    <p class="text-xs text-slate-500 mt-1">Akses mutlak untuk melihat seluruh data evaluasi se-provinsi. Admin dapat melihat analitik grafik, Ranking Guru berprestasi, dan mencetak Rekapitulasi.</p>
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
                                    <h4 class="font-bold text-slate-800 text-sm">Dashboard</h4>
                                    <p class="text-xs text-slate-500 mt-1">Ringkasan status kinerja khusus di lingkup sekolah yang dipimpinnya.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Data Guru & Asesor (Read-Only)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Melihat daftar guru dan asesor yang bertugas di sekolahnya untuk tujuan monitoring, tanpa hak untuk mengubah/menghapus data.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Data Evaluasi (Verifikasi & Approval)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Pintu gerbang terakhir sebelum nilai disahkan. Kepala Sekolah membuka draf yang "Selesai" (Completed) oleh Asesor, mengeceknya, lalu klik "Setujui" agar hasil menjadi sah.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Grafik Kinerja</h4>
                                    <p class="text-xs text-slate-500 mt-1">Melihat tren nilai rata-rata sekolah dari periode ke periode dalam bentuk visual (Pie/Bar/Line Chart).</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Ranking Guru</h4>
                                    <p class="text-xs text-slate-500 mt-1">Sistem otomatis menyortir performa guru terbaik (Top 3) di sekolahnya berdasarkan Nilai Akhir untuk referensi penghargaan atau promosi.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-emerald-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Rekapitulasi (Buku Induk)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Mencetak satu lembar PDF besar berisi seluruh daftar guru di sekolahnya beserta perolehan nilai, untuk ditandatangani.</p>
                                </div>
                            </div>
                        </div>

                        <!-- PENILAI -->
                        <div class="border border-slate-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <i data-lucide="user-check" class="w-8 h-8 text-blue-600"></i>
                                <h3 class="text-xl font-bold text-slate-900">3. Asesor / Penilai</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Dashboard</h4>
                                    <p class="text-xs text-slate-500 mt-1">Melihat progres tugas penilaian yang sedang ia tangani (Berapa Draft, Berapa yang Selesai).</p>
                                </div>
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Data Evaluasi</h4>
                                    <p class="text-xs text-slate-500 mt-1">Menu sentral bagi Penilai. Di sini Penilai memulai kuesioner baru untuk seorang guru. Penilai mengisi borang penilaian (Skala 1-4) beserta catatan setiap indikator.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Fitur Simpan Draf & Finalisasi</h4>
                                    <p class="text-xs text-slate-500 mt-1">Penilaian tidak harus selesai 1 hari. Asesor dapat menyimpan sebagai "Draft" atau "In Progress". Jika seluruh observasi telah mantap, barulah ubah ke "Selesai (Completed)".</p>
                                </div>
                                <div class="pl-4 border-l-2 border-blue-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Laporan Hasil Observasi</h4>
                                    <p class="text-xs text-slate-500 mt-1">Asesor dapat melihat daftar detail historis seluruh guru yang pernah ia berikan nilai beserta catatan asesornya.</p>
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
                                    <h4 class="font-bold text-slate-800 text-sm">Dashboard</h4>
                                    <p class="text-xs text-slate-500 mt-1">Menyambut guru dengan nilai akhir rata-rata (IPK) kinerjanya pada periode aktif.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-amber-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Data Evaluasi (Rapor Kinerja)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Guru hanya dapat melihat riwayat penilaian yang ditujukan untuk dirinya sendiri secara detail, mengawasi progres (apakah sudah disetujui atau masih draft), serta melihat detail komponen mana yang nilainya kurang/lebih.</p>
                                </div>
                                <div class="pl-4 border-l-2 border-amber-200">
                                    <h4 class="font-bold text-slate-800 text-sm">Menu Laporan Pribadi (Cetak)</h4>
                                    <p class="text-xs text-slate-500 mt-1">Akses satu klik untuk mencetak dokumen final Rapor Kinerja. Lembar cetak ini dilengkapi kop surat resmi sekolah dan siap ditandatangani untuk keperluan akreditasi/kenaikan pangkat.</p>
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
