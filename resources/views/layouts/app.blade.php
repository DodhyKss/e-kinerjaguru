<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - E-Kinerja Guru SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="h-full font-sans antialiased text-slate-900">
    <div class="min-h-full">
        <!-- Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

        <!-- Sidebar Navigation -->
        <nav id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white z-50 flex flex-col transition-transform duration-300 transform -translate-x-full lg:translate-x-0">
            <div class="flex items-center gap-3 h-16 px-6 bg-slate-900 border-b border-slate-800 shrink-0">
                <div class="bg-indigo-500 text-white p-1.5 rounded-lg shadow-sm">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <h1 class="text-lg font-bold tracking-wide text-white">EKG <span class="text-indigo-400">SMK</span></h1>
            </div>
            <div class="flex-1 overflow-y-auto min-h-0 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
                <div class="px-4 py-6 space-y-1">
                <!-- Dashboard (All Roles) -->
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                    <i data-lucide="layout-dashboard"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                    Dashboard
                </a>

                @if(auth()->user()->isAdmin())
                    <!-- Master Data (Admin Only) -->
                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
                    </div>

                    <a href="{{ route('schools.index') }}"
                        class="{{ request()->routeIs('schools.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="school"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('schools.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Data Sekolah
                    </a>

                    <a href="{{ route('gurus.index') }}"
                        class="{{ request()->routeIs('gurus.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="users"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('gurus.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Data Guru
                    </a>

                    <a href="{{ route('penilais.index') }}"
                        class="{{ request()->routeIs('penilais.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="user-check"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('penilais.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Data Asesor / Evaluator
                    </a>

                    <a href="{{ route('kepala-sekolahs.index') }}"
                        class="{{ request()->routeIs('kepala-sekolahs.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="user-cog"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('kepala-sekolahs.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Data Kepala Sekolah
                    </a>

                    <a href="{{ route('evaluation-periods.index') }}"
                        class="{{ request()->routeIs('evaluation-periods.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="calendar-clock"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('evaluation-periods.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Periode Evaluasi
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Wilayah</p>
                    </div>

                    <a href="{{ route('provinsis.index') }}"
                        class="{{ request()->routeIs('provinsis.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="map"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('provinsis.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Provinsi
                    </a>

                    <a href="{{ route('kabupatens.index') }}"
                        class="{{ request()->routeIs('kabupatens.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="map-pin"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('kabupatens.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Kabupaten
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Atribut Guru
                        </p>
                    </div>

                    <a href="{{ route('kelompok-mapels.index') }}"
                        class="{{ request()->routeIs('kelompok-mapels.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="layers"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('kelompok-mapels.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Kelompok Mapel
                    </a>

                    <a href="{{ route('mata-pelajarans.index') }}"
                        class="{{ request()->routeIs('mata-pelajarans.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="book"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('mata-pelajarans.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Mata Pelajaran
                    </a>

                    <a href="{{ route('kompetensi-keahlians.index') }}"
                        class="{{ request()->routeIs('kompetensi-keahlians.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="award"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('kompetensi-keahlians.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Kompetensi Keahlian
                    </a>

                    <a href="{{ route('pangkat-golongans.index') }}"
                        class="{{ request()->routeIs('pangkat-golongans.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="bar-chart"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('pangkat-golongans.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Pangkat / Golongan
                    </a>

                    <a href="{{ route('jabatan-fungsionals.index') }}"
                        class="{{ request()->routeIs('jabatan-fungsionals.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="briefcase"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('jabatan-fungsionals.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Jabatan Fungsional
                    </a>

                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Instrumen & Panduan</p>
                    </div>

                    <a href="{{ route('indicators.index') }}"
                        class="{{ request()->routeIs('indicators.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="book-open-check"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('indicators.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Master Instrumen
                    </a>

                    <a href="{{ route('guide-books.index') }}"
                        class="{{ request()->routeIs('guide-books.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="file-text"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('guide-books.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Buku Panduan (PDF)
                    </a>
                @endif

                @if(auth()->user()->isKepalaSekolah())
                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
                    </div>
                    <a href="{{ route('gurus.index') }}"
                        class="{{ request()->routeIs('gurus.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="users"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('gurus.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Data Guru
                    </a>

                    <a href="{{ route('penilais.index') }}"
                        class="{{ request()->routeIs('penilais.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                        <i data-lucide="user-check"
                            class="mr-3 h-5 w-5 {{ request()->routeIs('penilais.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                        Data Asesor / Evaluator
                    </a>
                @endif

                <!-- Evaluasi Kinerja (All Roles) -->
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Evaluasi</p>
                </div>
                <a href="{{ route('evaluations.index') }}"
                    class="{{ request()->routeIs('evaluations.index') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 mb-1">
                    <i data-lucide="clipboard-list"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('evaluations.index') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                    Data Evaluasi
                </a>

                @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah() || auth()->user()->isPenilai())
                <a href="{{ route('evaluations.rekomendasis.index') }}"
                    class="{{ request()->routeIs('evaluations.rekomendasis.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 mb-1">
                    <i data-lucide="message-square-plus"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('evaluations.rekomendasis.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                    Rekomendasi
                </a>
                @endif

                <a href="{{ route('reports.index') }}"
                    class="{{ request()->routeIs('reports.index') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 mb-1">
                    <i data-lucide="printer"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('reports.index') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                    Laporan
                </a>

                @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
                <a href="{{ route('reports.grafik') }}"
                    class="{{ request()->routeIs('reports.grafik') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 mb-1">
                    <i data-lucide="bar-chart-3"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('reports.grafik') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                    Grafik Kinerja
                </a>

                <a href="{{ route('reports.ranking') }}"
                    class="{{ request()->routeIs('reports.ranking') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 mb-1">
                    <i data-lucide="award"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('reports.ranking') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                    Ranking Guru
                </a>

                <a href="{{ route('reports.recap') }}"
                    class="{{ request()->routeIs('reports.recap') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                    <i data-lucide="clipboard-list"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('reports.recap') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}"></i>
                    Rekapitulasi
                </a>
                @endif
                </div>
            </div>
        </nav>

        <!-- Main Content area -->
        <div class="lg:pl-64 flex flex-col min-h-screen transition-all duration-300">
            <!-- Topbar -->
            <header
                class="h-16 bg-white/80 backdrop-blur-xl border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()" class="lg:hidden text-slate-500 hover:text-slate-700 focus:outline-none p-2 -ml-2 rounded-lg hover:bg-slate-100">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h2 class="text-lg font-semibold text-slate-800 truncate max-w-[200px] sm:max-w-none">@yield('title')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                            {{ auth()->user()->initials }}
                        </div>
                        <div class="text-sm hidden sm:block">
                            <p class="font-medium text-slate-700 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-slate-500 text-xs mt-1 capitalize">
                                {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-slate-200 mx-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium text-slate-500 hover:text-red-600 flex items-center transition-colors">
                            <i data-lucide="log-out" class="sm:mr-2 h-5 w-5 sm:h-4 sm:w-4"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-4 sm:p-8 overflow-x-hidden w-full max-w-full">
                @if (session('success'))
                    <div
                        class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm border border-emerald-100 flex items-center">
                        <i data-lucide="check-circle" class="mr-3 h-5 w-5 text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 flex items-center">
                        <i data-lucide="alert-circle" class="mr-3 h-5 w-5 text-red-500"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="bg-white border-t border-slate-200 mt-auto">
                <div class="px-4 sm:px-8 py-5 flex flex-col sm:flex-row items-center justify-between text-sm gap-2 text-center sm:text-left">
                    <div class="text-slate-500 font-medium">
                        &copy; {{ date('Y') }} <span class="text-indigo-600 font-semibold">E-Kinerja Guru SMK</span>.
                        <span class="hidden sm:inline">Hak Cipta Dilindungi.</span>
                    </div>
                    <div class="text-slate-400 flex items-center justify-center gap-2 sm:gap-4">
                        <span class="hidden sm:inline">Dikelola oleh Admin Pusat</span>
                        <span class="hidden sm:inline w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span>Versi 1.0.0</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>

</html>