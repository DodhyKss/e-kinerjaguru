@extends('layouts.app')
@section('title', 'Dashboard Penilai')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center">
            <i data-lucide="clipboard-list" class="h-6 w-6 text-slate-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Ditugaskan</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_assigned'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center">
            <i data-lucide="clock" class="h-6 w-6 text-amber-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Dalam Proses</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['in_progress'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center">
            <i data-lucide="check-circle" class="h-6 w-6 text-emerald-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Selesai</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['completed'] }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100">
        <h3 class="text-lg font-medium text-slate-900">Daftar Evaluasi Anda</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Guru Yang Dinilai</th>
                    <th class="px-6 py-4 font-semibold">Periode</th>
                    <th class="px-6 py-4 font-semibold">Progres</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eval)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $eval->guru->nama }}</td>
                    <td class="px-6 py-4">{{ $eval->evaluationPeriod->nama }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-slate-200 rounded-full h-2 max-w-[100px]">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $eval->progress }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500">{{ $eval->progress }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($eval->status == 'completed' || $eval->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Selesai</span>
                        @elseif($eval->status == 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Sedang Berjalan</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Belum Dimulai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($eval->status == 'completed' || $eval->status == 'approved')
                            <a href="{{ route('evaluations.show', $eval) }}" class="text-slate-600 hover:text-indigo-900 font-medium text-xs border border-slate-200 px-3 py-1.5 rounded-lg bg-white">Lihat Hasil</a>
                        @else
                            <a href="{{ route('evaluations.show', $eval) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium text-xs px-3 py-1.5 rounded-lg transition-colors">Lanjutkan Penilaian</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">Belum ada tugas evaluasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
