@extends('layouts.app')
@section('title', 'Manajemen Akun Pengguna')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-slate-50 gap-4">
        <div>
            <h3 class="text-lg font-medium text-slate-900">Daftar Akun Pengguna</h3>
            <p class="text-xs text-slate-500 mt-1">Mengelola akses login dan mereset password pengguna.</p>
        </div>
        <form action="{{ route('users.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row gap-3" id="searchForm">
            <div class="relative">
                <select name="school_id" class="select2-school w-full sm:w-64" onchange="document.getElementById('searchForm').submit()">
                    <option value="">Semua Sekolah...</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="relative">
                <select name="search" class="select2-search w-full sm:w-64" onchange="document.getElementById('searchForm').submit()">
                    <option value="">Semua Akun Pengguna...</option>
                    @foreach($allUsers as $u)
                        <option value="{{ $u->name }}" {{ request('search') == $u->name ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('search') || request('school_id'))
                <div class="flex items-center">
                    <a href="{{ route('users.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Reset Filter</a>
                </div>
            @endif
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nama & Email</th>
                    <th class="px-6 py-4 font-semibold">Profil Ganda / Jabatan</th>
                    <th class="px-6 py-4 font-semibold">Unit Sekolah</th>
                    <th class="px-6 py-4 font-semibold">Status Akun</th>
                    <th class="px-6 py-4 font-semibold">Aksi Password</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                        <div class="mt-1">
                            @if($user->role == 'admin')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Admin Utama</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800">Role Dasar: {{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @if($user->guru)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    <i data-lucide="book-open" class="w-3 h-3 mr-1"></i> Profil Guru
                                </span>
                            @endif
                            @if($user->penilai)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                    <i data-lucide="check-square" class="w-3 h-3 mr-1"></i> Profil Asesor
                                </span>
                            @endif
                            @if($user->kepalaSekolah)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                    <i data-lucide="award" class="w-3 h-3 mr-1"></i> Profil Kepsek
                                </span>
                            @endif
                            @if(!$user->guru && !$user->penilai && !$user->kepalaSekolah && $user->role != 'admin')
                                <span class="text-xs text-slate-400 italic">Belum melengkapi profil master</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->guru && $user->guru->school)
                            {{ $user->guru->school->nama }}
                        @elseif($user->kepalaSekolah && $user->kepalaSekolah->school)
                            {{ $user->kepalaSekolah->school->nama }}
                        @elseif($user->school)
                            {{ $user->school->nama }}
                        @else
                            <span class="text-slate-400 italic">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-start gap-2">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <i data-lucide="check-circle-2" class="w-3 h-3 mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Nonaktif
                                </span>
                            @endif
                            @if(auth()->user()->id !== $user->id)
                                @if($user->is_active)
                                <form action="{{ route('users.toggle-active', $user) }}" method="POST" onsubmit="return confirm('Nonaktifkan akun {{ $user->name }}? Pengguna tidak akan bisa login sampai akunnya diaktifkan kembali.');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center text-xs font-bold text-red-700 bg-red-50 border border-red-200 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                        <i data-lucide="user-x" class="w-3 h-3 mr-1.5"></i> Nonaktifkan
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('users.toggle-active', $user) }}" method="POST" onsubmit="return confirm('Aktifkan kembali akun {{ $user->name }}?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                        <i data-lucide="user-check" class="w-3 h-3 mr-1.5"></i> Aktifkan
                                    </button>
                                </form>
                                @endif
                            @else
                                <span class="text-[10px] text-slate-400 italic">Akun Anda sendiri</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($user->role != 'admin' || auth()->user()->id == $user->id)
                        <button type="button" onclick="openResetPasswordModal('{{ route('users.reset-password', $user) }}', '{{ addslashes($user->name) }}')" class="inline-flex items-center text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                            <i data-lucide="key-round" class="w-3 h-3 mr-1.5"></i> Reset Password
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="users" class="h-10 w-10 text-slate-300 mb-3"></i>
                            <p>Belum ada data pengguna.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Penjelasan Keamanan Password -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-5 flex gap-4">
    <div class="flex-shrink-0">
        <i data-lucide="shield-check" class="w-6 h-6 text-blue-600"></i>
    </div>
    <div>
        <h4 class="text-sm font-bold text-blue-900">Catatan Keamanan (Enkripsi Password)</h4>
        <p class="text-sm text-blue-800 mt-1">Sesuai dengan standar keamanan sistem modern, semua password pengguna dienkripsi dengan algoritma <i>Bcrypt/Argon2</i> sebelum disimpan ke dalam database. Oleh karena itu, <strong>Admin maupun sistem tidak dapat melihat password asli (teks biasa) milik pengguna.</strong></p>
        <p class="text-sm text-blue-800 mt-2">Jika ada guru yang lupa password, Admin dapat menekan tombol <strong>"Reset Password"</strong> di atas lalu <strong>menginput password baru yang diinginkan</strong> (minimal 8 karakter). Setelah itu, guru tersebut bisa login kembali menggunakan password baru tersebut.</p>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeResetPasswordModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
        <form id="resetPasswordForm" method="POST" action="" onsubmit="return validateResetPasswordForm();">
            @csrf
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="bg-amber-100 text-amber-700 p-2 rounded-lg">
                        <i data-lucide="key-round" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Reset Password</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Akun: <span id="resetPasswordUserName" class="font-semibold text-slate-700"></span></p>
                    </div>
                </div>
                <button type="button" onclick="closeResetPasswordModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="new_password" required minlength="8" autocomplete="new-password"
                        placeholder="Masukkan password baru (min. 8 karakter)"
                        class="w-full rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-200 transition-shadow">
                </div>
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="new_password_confirmation" required minlength="8" autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="w-full rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-200 transition-shadow">
                </div>
                <p id="resetPasswordError" class="hidden text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2"></p>
                <div class="bg-blue-50 border border-blue-100 rounded-lg px-3 py-2.5 text-xs text-blue-800 flex gap-2">
                    <i data-lucide="info" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <span>Password minimal 8 karakter. Disarankan kombinasi huruf besar, huruf kecil, angka, dan simbol. Berikan password ini kepada pengguna terkait.</span>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition-colors">Batal</button>
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="check" class="w-4 h-4 mr-1.5"></i> Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-search').select2({
            placeholder: "Cari dan pilih nama pengguna...",
            allowClear: true,
            width: '100%'
        });
        $('.select2-school').select2({
            placeholder: "Pilih Sekolah...",
            allowClear: true,
            width: '100%'
        });
    });

    function openResetPasswordModal(actionUrl, userName) {
        const form = document.getElementById('resetPasswordForm');
        form.action = actionUrl;
        document.getElementById('resetPasswordUserName').textContent = userName;
        document.getElementById('new_password').value = '';
        document.getElementById('new_password_confirmation').value = '';
        document.getElementById('resetPasswordError').classList.add('hidden');

        const modal = document.getElementById('resetPasswordModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('new_password').focus(), 100);
    }

    function closeResetPasswordModal() {
        const modal = document.getElementById('resetPasswordModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function validateResetPasswordForm() {
        const password = document.getElementById('new_password').value;
        const confirmation = document.getElementById('new_password_confirmation').value;
        const errorBox = document.getElementById('resetPasswordError');

        if (password.length < 8) {
            errorBox.textContent = 'Password minimal harus 8 karakter.';
            errorBox.classList.remove('hidden');
            return false;
        }
        if (password !== confirmation) {
            errorBox.textContent = 'Konfirmasi password tidak cocok dengan password baru.';
            errorBox.classList.remove('hidden');
            return false;
        }
        return confirm('Anda yakin ingin mereset password akun ini dengan password baru yang telah diinput?');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeResetPasswordModal();
    });
</script>
@endpush

@endsection
