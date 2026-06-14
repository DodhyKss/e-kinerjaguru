@extends('layouts.app')
@section('title', 'Laporan Evaluasi Kinerja')

@section('content')
<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900">Filter Laporan</h3>
        </div>
        
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="period_id" class="block text-xs font-medium text-slate-500 mb-1">Periode Evaluasi</label>
                <select name="period_id" id="period_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="">Semua Periode</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                            {{ $period->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-xs font-medium text-slate-500 mb-1">Status Penilaian</label>
                <select name="status" id="status" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Proses Penilaian</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Menunggu Review Kepsek</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui Kepala Sekolah</option>
                </select>
            </div>

            @if(auth()->user()->isAdmin())
            <div>
                <label for="school_id" class="block text-xs font-medium text-slate-500 mb-1">Sekolah (Unit Kerja)</label>
                <select name="school_id" id="school_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="">Semua Sekolah</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
            <div>
                <label for="guru_name" class="block text-xs font-medium text-slate-500 mb-1">Nama Guru</label>
                <input type="text" name="guru_name" id="guru_name" value="{{ request('guru_name') }}" placeholder="Cari nama guru..." class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>
            @endif

            <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                    Reset Filter
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Hasil Pencarian Laporan</h3>
        <span class="text-sm text-slate-500">Menampilkan {{ $evaluations->total() }} data</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-100/50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3">Periode</th>
                    <th class="px-6 py-3">Guru Yang Dinilai</th>
                    @if(!auth()->user()->isGuru())
                        <th class="px-6 py-3">Unit Kerja</th>
                    @endif
                    <th class="px-6 py-3">Asesor/Penilai</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Skor (Rata-rata)</th>
                    <th class="px-6 py-3">Rekomendasi</th>
                    <th class="px-6 py-3 text-right">Aksi Laporan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eval)
                <tr class="bg-white border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $eval->evaluationPeriod->nama }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $eval->guru->nama }}</div>
                        <div class="text-xs text-slate-500">{{ $eval->guru->mata_pelajaran }}</div>
                    </td>
                    @if(!auth()->user()->isGuru())
                        <td class="px-6 py-4 text-slate-600">
                            {{ $eval->guru->school->nama ?? '-' }}
                        </td>
                    @endif
                    <td class="px-6 py-4">
                        @if(auth()->user()->isPenilai())
                            <span class="text-indigo-600 font-medium">Anda</span>
                        @else
                            {{ $eval->penilai->nama }}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($eval->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Disetujui</span>
                        @elseif($eval->status == 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Menunggu Review</span>
                        @elseif($eval->status == 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Proses Penilaian</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Belum Dimulai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($eval->rata_rata)
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-indigo-600">{{ $eval->rata_rata }}</span>
                                <span class="text-xs text-slate-400">/ 4.0</span>
                            </div>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-600">
                        @if($eval->rekomendasi)
                            <span class="line-clamp-2" title="{{ $eval->rekomendasi->rekomendasi }}">
                                {{ $eval->rekomendasi->rekomendasi }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('evaluations.show', $eval) }}" class="inline-flex items-center text-xs font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                <i data-lucide="eye" class="w-3 h-3 mr-1"></i> Detail
                            </a>
                            <a href="{{ route('evaluations.report', $eval) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-white bg-slate-800 hover:bg-slate-900 px-2.5 py-1.5 rounded-lg transition-colors shadow-sm">
                                <i data-lucide="printer" class="w-3 h-3 mr-1"></i> Cetak
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="file-search" class="h-10 w-10 text-slate-300 mb-3"></i>
                            <p>Tidak ada laporan yang sesuai dengan filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($evaluations->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $evaluations->links() }}
    </div>
    @endif
</div>
@endsection
