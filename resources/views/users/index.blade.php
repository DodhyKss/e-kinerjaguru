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
                    <td class="px-6 py-4 text-right">
                        @if($user->role != 'admin' || auth()->user()->id == $user->id)
                        <form action="{{ route('users.reset-password', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin mereset password akun {{ $user->name }} menjadi 12345678 ?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                <i data-lucide="key-round" class="w-3 h-3 mr-1.5"></i> Reset Password
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center">
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
        <p class="text-sm text-blue-800 mt-2">Jika ada guru yang lupa password, solusi terbaik adalah menekan tombol <strong>"Reset Password"</strong> di atas. Password mereka akan dikembalikan ke <i>default</i> yaitu <code>12345678</code>, kemudian guru tersebut bisa login kembali dengan password tersebut.</p>
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
</script>
@endpush

@endsection
