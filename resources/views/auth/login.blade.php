<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - E-Kinerja Guru SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-indigo-900">E-Kinerja Guru</h1>
            <p class="mt-2 text-sm text-slate-600">Sistem Evaluasi Kinerja Guru Pendidikan Vokasi</p>
        </div>

        <div class="bg-white/80 backdrop-blur-xl shadow-xl shadow-indigo-100/50 rounded-2xl overflow-hidden border border-white">
            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-200">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-200">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="remember" class="ml-2 block text-sm text-slate-700">Remember me</label>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200 transform hover:-translate-y-0.5">
                            Sign in to your account
                        </button>
                    </div>
                </form>
            </div>
            <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">Gunakan akun demo untuk pengujian:</p>
                <div class="mt-2 flex justify-center space-x-2 text-xs font-medium text-slate-600">
                    <span class="bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">admin@ekg.local</span>
                    <span class="bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">penilai@ekg.local</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
