@extends('layouts.app')
@section('title', 'Dashboard Anda')

@section('content')

@if($isPenilai)
    <div class="mb-2">
        <h2 class="text-xl font-bold text-slate-800">Tugas Anda Sebagai Asesor / Penilai</h2>
        <p class="text-sm text-slate-500 mb-6">Ringkasan tugas evaluasi kinerja guru yang dibebankan kepada Anda.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center">
                <i data-lucide="clipboard-list" class="h-6 w-6 text-slate-600"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Ditugaskan</p>
                <p class="text-2xl font-bold text-slate-900">{{ $penilaiStats['total_assigned'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center">
                <i data-lucide="clock" class="h-6 w-6 text-amber-600"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Dalam Proses</p>
                <p class="text-2xl font-bold text-slate-900">{{ $penilaiStats['in_progress'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center">
                <i data-lucide="check-circle" class="h-6 w-6 text-emerald-600"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Selesai</p>
                <p class="text-2xl font-bold text-slate-900">{{ $penilaiStats['completed'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-12">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-lg font-medium text-slate-900">Daftar Evaluasi Anda (Penugasan)</h3>
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
                    @forelse($penilaiEvaluations as $eval)
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
@endif

@if($isGuru)
    @if($isPenilai)
    <!-- Divider untuk pemisah profil ganda -->
    <div class="relative py-4 mb-8">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="bg-slate-50 px-3 text-sm font-bold text-slate-400 uppercase tracking-widest"><i data-lucide="user" class="w-4 h-4 inline mr-1 -mt-1"></i> Beralih ke Profil Guru</span>
        </div>
    </div>
    @endif

    <div class="mb-2">
        <h2 class="text-xl font-bold text-slate-800">Kinerja Anda Sebagai Guru</h2>
        <p class="text-sm text-slate-500 mb-6">Informasi mengenai evaluasi kinerja Anda yang dilakukan oleh Asesor.</p>
    </div>

    @if(isset($guruCurrentEvaluation) && $guruCurrentEvaluation)
    <div class="mb-8">
        <h3 class="text-lg font-medium text-slate-900 mb-4">Evaluasi Saat Ini</h3>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-indigo-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4">
                @if($guruCurrentEvaluation->status == 'completed' || $guruCurrentEvaluation->status == 'approved')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Evaluasi Selesai</span>
                @elseif($guruCurrentEvaluation->status == 'in_progress')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Sedang Dinilai</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Belum Dimulai</span>
                @endif
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <p class="text-sm font-medium text-indigo-600 mb-1">{{ $guruCurrentEvaluation->evaluationPeriod->nama }}</p>
                    <h4 class="text-2xl font-bold text-slate-900 mb-4">Penilaian Kinerja Berlangsung</h4>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Penilai/Asesor</p>
                            <p class="text-sm font-medium text-slate-800">{{ $guruCurrentEvaluation->penilai->nama }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Progres</p>
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $guruCurrentEvaluation->progress }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-slate-600">{{ $guruCurrentEvaluation->progress }}%</span>
                            </div>
                        </div>
                    </div>

                    @if($guruCurrentEvaluation->status == 'completed' || $guruCurrentEvaluation->status == 'approved')
                        <a href="{{ route('evaluations.show', $guruCurrentEvaluation) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
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
                
                @if($guruCurrentEvaluation->status == 'completed' || $guruCurrentEvaluation->status == 'approved')
                <div class="w-full md:w-64 flex flex-col justify-center bg-slate-50 rounded-xl p-6 border border-slate-100 text-center">
                    <p class="text-sm text-slate-500 mb-2">Skor Rata-rata Anda</p>
                    <div class="text-5xl font-black text-indigo-600 mb-2">{{ $guruCurrentEvaluation->rata_rata }}<span class="text-lg text-slate-400 font-medium">/4.0</span></div>
                    <p class="text-xs text-slate-500">Telah disetujui Kepala Sekolah</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-lg font-medium text-slate-900">Riwayat Evaluasi (Sebagai Guru)</h3>
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
                    @forelse($guruHistoryEvaluations as $eval)
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
@endif

@endsection
