@extends('layouts.app')
@section('title', 'Dashboard Admin Internal Sekolah')

@section('content')
<div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-100 px-6 py-5 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-teal-50 flex items-center justify-center">
            <i data-lucide="building-2" class="h-6 w-6 text-teal-600"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">{{ $school->nama ?? 'Sekolah Anda' }}</h3>
            <p class="text-sm text-slate-500 mt-0.5">
                @if($school && $school->kabupaten)
                    {{ $school->kabupaten->nama }}{{ $school->provinsi ? ', ' . $school->provinsi->nama : '' }}
                @else
                    Admin Internal Sekolah
                @endif
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
        <i data-lucide="info" class="w-4 h-4 text-slate-400"></i>
        Seluruh data di halaman ini hanya untuk sekolah Anda.
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center">
            <i data-lucide="users" class="h-6 w-6 text-indigo-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Guru</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_gurus'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-purple-50 flex items-center justify-center">
            <i data-lucide="check-square" class="h-6 w-6 text-purple-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Asesor</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_penilais'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center">
            <i data-lucide="clipboard-list" class="h-6 w-6 text-amber-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Evaluasi</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['evaluations_total'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center">
            <i data-lucide="badge-check" class="h-6 w-6 text-emerald-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Evaluasi Selesai</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['evaluations_completed'] + $stats['evaluations_approved'] }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Disetujui: {{ $stats['evaluations_approved'] }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('gurus.create') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:border-indigo-200 hover:shadow-md transition-all">
        <div class="h-11 w-11 rounded-xl bg-indigo-50 flex items-center justify-center">
            <i data-lucide="user-plus" class="h-5 w-5 text-indigo-600"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-900">Tambah Data Guru</p>
            <p class="text-xs text-slate-500">Input guru di sekolah Anda</p>
        </div>
    </a>
    <a href="{{ route('penilais.create') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:border-indigo-200 hover:shadow-md transition-all">
        <div class="h-11 w-11 rounded-xl bg-purple-50 flex items-center justify-center">
            <i data-lucide="user-plus" class="h-5 w-5 text-purple-600"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-900">Tambah Data Asesor</p>
            <p class="text-xs text-slate-500">Input asesor di sekolah Anda</p>
        </div>
    </a>
    <a href="{{ route('users.index') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:border-indigo-200 hover:shadow-md transition-all">
        <div class="h-11 w-11 rounded-xl bg-amber-50 flex items-center justify-center">
            <i data-lucide="key-round" class="h-5 w-5 text-amber-600"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-900">Kelola Akun</p>
            <p class="text-xs text-slate-500">Reset password guru &amp; asesor</p>
        </div>
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-lg font-medium text-slate-900">Evaluasi Terbaru</h3>
        <a href="{{ route('evaluations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Guru</th>
                    <th class="px-6 py-4 font-semibold">Asesor</th>
                    <th class="px-6 py-4 font-semibold">Periode</th>
                    <th class="px-6 py-4 font-semibold">Skor Rata-rata</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEvaluations as $eval)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $eval->guru->nama }}</td>
                    <td class="px-6 py-4">{{ $eval->penilai->nama }}</td>
                    <td class="px-6 py-4">{{ $eval->evaluationPeriod->nama ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $eval->rata_rata ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($eval->status == 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu Review</span>
                        @elseif($eval->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Disetujui</span>
                        @elseif($eval->status == 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Sedang Berjalan</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Draft</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('evaluations.show', $eval) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">Belum ada data evaluasi untuk sekolah Anda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection