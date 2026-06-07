<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - E-Kinerja Guru SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <div class="flex min-h-screen">
        <!-- Left Side: Branding / Showcase (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 overflow-hidden items-center justify-center">
            <!-- Decorative Background Elements -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-indigo-600/30 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-purple-600/30 blur-3xl"></div>
            
            <div class="relative z-10 px-12 lg:px-20 text-white max-w-2xl">
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl inline-flex mb-8 shadow-inner border border-white/10">
                    <i data-lucide="graduation-cap" class="w-12 h-12"></i>
                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-6 leading-tight">
                    Optimalkan Potensi,<br>
                    <span class="text-indigo-300">Tingkatkan Kinerja.</span>
                </h1>
                <p class="text-lg text-indigo-100/80 mb-10 leading-relaxed font-light">
                    Sistem Evaluasi Kinerja Guru (E-Kinerja) SMK. Sebuah platform modern terintegrasi untuk pengelolaan, pemantauan, dan penilaian akademik secara komprehensif dan transparan.
                </p>
                
                <div class="flex items-center gap-4 text-sm font-medium text-indigo-200">
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full border border-white/5 backdrop-blur-sm">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i> Aman
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full border border-white/5 backdrop-blur-sm">
                        <i data-lucide="zap" class="w-4 h-4 text-amber-400"></i> Cepat
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full border border-white/5 backdrop-blur-sm">
                        <i data-lucide="bar-chart-2" class="w-4 h-4 text-blue-400"></i> Terukur
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-20 relative z-10 bg-slate-50">
            <!-- Mobile Brand Header -->
            <div class="lg:hidden text-center mb-8">
                <div class="mx-auto bg-indigo-600 text-white p-3 rounded-2xl inline-flex mb-4 shadow-lg shadow-indigo-200">
                    <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">E-Kinerja SMK</h1>
            </div>

            <div class="w-full max-w-md">
                <div class="glass-panel shadow-2xl shadow-slate-200/50 rounded-3xl overflow-hidden border border-white/60">
                    <div class="p-8 sm:p-10">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-slate-900">Selamat Datang</h2>
                            <p class="mt-2 text-sm text-slate-500">Silakan masukkan kredensial akun Anda untuk masuk ke dalam sistem.</p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-6 bg-rose-50 text-rose-600 px-4 py-3 rounded-xl text-sm border border-rose-100 flex items-start animate-fade-in">
                                <i data-lucide="alert-circle" class="w-5 h-5 mr-3 shrink-0 mt-0.5"></i>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            <div class="space-y-1">
                                <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i data-lucide="mail" class="w-5 h-5"></i>
                                    </div>
                                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all duration-200 shadow-sm"
                                        placeholder="nama@sekolah.sch.id">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i data-lucide="lock" class="w-5 h-5"></i>
                                    </div>
                                    <input id="password" name="password" type="password" autocomplete="current-password" required
                                        class="block w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all duration-200 shadow-sm"
                                        placeholder="••••••••">
                                    <button type="button" onclick="togglePassword()" tabindex="-1" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                                        <span id="icon-show"><i data-lucide="eye" class="w-5 h-5"></i></span>
                                        <span id="icon-hide" class="hidden"><i data-lucide="eye-off" class="w-5 h-5"></i></span>
                                    </button>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all duration-200 transform hover:-translate-y-0.5 mt-2">
                                Masuk ke Dashboard <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Footer Info (Demo Accounts & Guide) -->
                    <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-100 backdrop-blur-sm flex flex-col items-center">
                        <a href="{{ route('panduan') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white border border-indigo-100 text-sm font-medium text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-all shadow-sm w-full">
                            <i data-lucide="book-open" class="w-4 h-4 mr-2"></i> Pelajari Cara Menggunakan E-Kinerja
                        </a>
                    </div>
                </div>
                
                <p class="text-center text-xs text-slate-400 mt-8">
                    &copy; {{ date('Y') }} Pendidikan Vokasi. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const iconShow = document.getElementById('icon-show');
            const iconHide = document.getElementById('icon-hide');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                iconShow.classList.add('hidden');
                iconHide.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                iconShow.classList.remove('hidden');
                iconHide.classList.add('hidden');
            }
        }

        lucide.createIcons();
    </script>
</body>
</html>
