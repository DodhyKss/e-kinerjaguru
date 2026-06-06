@extends('layouts.app')
@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
        <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center">
            <i data-lucide="check-circle" class="h-6 w-6 text-blue-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Evaluasi Selesai (Menunggu Review)</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['evaluations_completed'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center">
            <i data-lucide="badge-check" class="h-6 w-6 text-emerald-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Evaluasi Disetujui</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['evaluations_approved'] }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-lg font-medium text-slate-900">Evaluasi Terbaru</h3>
        <a href="{{ route('evaluations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3">Guru</th>
                    <th class="px-6 py-3">Penilai</th>
                    <th class="px-6 py-3">Skor Rata-rata</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEvaluations as $eval)
                <tr class="bg-white border-b border-slate-50 hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $eval->guru->nama }}</td>
                    <td class="px-6 py-4">{{ $eval->penilai->nama }}</td>
                    <td class="px-6 py-4 font-bold text-indigo-600">{{ $eval->rata_rata ?? '-' }}</td>
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
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data evaluasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
