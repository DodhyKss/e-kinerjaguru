@extends('layouts.app')
@section('title', 'Dashboard Guru')

@section('content')
@if($currentEvaluation)
<div class="mb-8">
    <h3 class="text-lg font-medium text-slate-900 mb-4">Evaluasi Saat Ini</h3>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-indigo-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4">
            @if($currentEvaluation->status == 'completed' || $currentEvaluation->status == 'approved')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Evaluasi Selesai</span>
            @elseif($currentEvaluation->status == 'in_progress')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Sedang Dinilai</span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Belum Dimulai</span>
            @endif
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <p class="text-sm font-medium text-indigo-600 mb-1">{{ $currentEvaluation->evaluationPeriod->nama }}</p>
                <h4 class="text-2xl font-bold text-slate-900 mb-4">Penilaian Kinerja Berlangsung</h4>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Penilai/Asesor</p>
                        <p class="text-sm font-medium text-slate-800">{{ $currentEvaluation->penilai->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Status Progres</p>
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $currentEvaluation->progress }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-slate-600">{{ $currentEvaluation->progress }}%</span>
                        </div>
                    </div>
                </div>

                @if($currentEvaluation->status == 'completed' || $currentEvaluation->status == 'approved')
                    <a href="{{ route('evaluations.show', $currentEvaluation) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        Lihat Hasil Evaluasi
                    </a>
                @else
                    <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                        <div class="flex items-start">
                            <i data-lucide="info" class="h-5 w-5 text-indigo-500 mt-0.5 mr-3 flex-shrink-0"></i>
                            <p class="text-sm text-indigo-800">Penilaian sedang berlangsung. Harap persiapkan dokumen pendukung RPP, Modul Ajar, Portofolio, dan perangkat lainnya untuk tahapan telaah dokumen dan wawancara bersama Asesor.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            @if($currentEvaluation->status == 'completed' || $currentEvaluation->status == 'approved')
            <div class="w-full md:w-64 flex flex-col justify-center bg-slate-50 rounded-xl p-6 border border-slate-100 text-center">
                <p class="text-sm text-slate-500 mb-2">Skor Rata-rata Anda</p>
                <div class="text-5xl font-black text-indigo-600 mb-2">{{ $currentEvaluation->rata_rata }}<span class="text-lg text-slate-400 font-medium">/4.0</span></div>
                <p class="text-xs text-slate-500">Telah disetujui Kepala Sekolah</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100">
        <h3 class="text-lg font-medium text-slate-900">Riwayat Evaluasi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Periode</th>
                    <th class="px-6 py-4 font-semibold">Skor Total</th>
                    <th class="px-6 py-4 font-semibold">Skor Rata-rata</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historyEvaluations as $eval)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $eval->evaluationPeriod->nama }}</td>
                    <td class="px-6 py-4">{{ $eval->total_skor }}</td>
                    <td class="px-6 py-4">{{ $eval->rata_rata }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eval->status == 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $eval->status == 'approved' ? 'Disetujui' : 'Selesai' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('evaluations.show', $eval) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">Belum ada riwayat evaluasi yang selesai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
