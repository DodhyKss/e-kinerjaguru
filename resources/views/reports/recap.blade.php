@extends('layouts.app')
@section('title', 'Rekapitulasi Kinerja')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Rekapitulasi Kinerja Guru</h2>
        <p class="text-sm text-slate-500 mt-1">Laporan menyeluruh (Buku Induk) hasil evaluasi semua guru.</p>
    </div>
    
    @if($selectedPeriod && $selectedSchool)
    <a href="{{ route('reports.recap', request()->all() + ['print' => 1]) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900 transition-colors shadow-sm inline-flex items-center">
        <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Rekapitulasi
    </a>
    @endif
</div>

<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900">Filter Rekapitulasi</h3>
        </div>
        
        <form action="{{ route('reports.recap') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="period_id" class="block text-xs font-medium text-slate-500 mb-1">Periode Evaluasi <span class="text-red-500">*</span></label>
                <select name="period_id" id="period_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ $periodId == $period->id ? 'selected' : '' }}>
                            {{ $period->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(auth()->user()->isAdmin())
            <div>
                <label for="school_id" class="block text-xs font-medium text-slate-500 mb-1">Sekolah (Unit Kerja) <span class="text-red-500">*</span></label>
                <select name="school_id" id="school_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="{{ auth()->user()->isAdmin() ? '' : 'md:col-span-2' }}">
                <label for="guru_name" class="block text-xs font-medium text-slate-500 mb-1">Nama Guru</label>
                <input type="text" name="guru_name" id="guru_name" value="{{ request('guru_name') }}" placeholder="Opsional..." class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>

            <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                <a href="{{ route('reports.recap') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center shadow-sm">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i> Tampilkan Rekapitulasi
                </button>
            </div>
        </form>
    </div>
</div>

@if(!$schoolId && auth()->user()->isAdmin())
    <div class="bg-blue-50 text-blue-800 p-6 rounded-2xl border border-blue-100 flex flex-col items-center justify-center text-center">
        <i data-lucide="info" class="w-10 h-10 text-blue-400 mb-3"></i>
        <h3 class="text-lg font-bold mb-1">Pilih Sekolah Terlebih Dahulu</h3>
        <p class="text-sm">Silakan pilih Periode dan Sekolah dari form filter di atas untuk melihat rekapitulasi data seluruh guru.</p>
    </div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Data Rekapitulasi</h3>
            <p class="text-xs text-slate-500">{{ $selectedSchool->nama ?? '' }} • Periode: {{ $selectedPeriod->nama ?? '' }}</p>
        </div>
        <span class="text-sm text-slate-500">Total: {{ $gurus->count() }} Guru</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-100/50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama Guru / NIP</th>
                    <th class="px-6 py-4">Mata Pelajaran</th>
                    <th class="px-6 py-4">Status Evaluasi</th>
                    <th class="px-6 py-4">Asesor / Penilai</th>
                    <th class="px-6 py-4 text-center">Total Skor</th>
                    <th class="px-6 py-4 text-center">Nilai Akhir</th>
                    <th class="px-6 py-4">Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $index => $guru)
                    @php
                        // Since we filtered evaluations by period_id in the eager load, 
                        // it should only have 0 or 1 evaluation for this period.
                        $eval = $guru->evaluations->first();
                    @endphp
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 text-slate-500">{{ $gurus->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $guru->nama }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $guru->nip ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $guru->mata_pelajaran }}
                    </td>
                    <td class="px-6 py-4">
                        @if($eval)
                            @if($eval->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-wider">Disetujui</span>
                            @elseif($eval->status == 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800 border border-blue-200 uppercase tracking-wider">Selesai</span>
                            @elseif($eval->status == 'in_progress')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-wider">Proses</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wider">Draft</span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-600 border border-red-200 uppercase tracking-wider">Belum Dimulai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $eval->penilai->nama ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($eval && $eval->total_skor !== null)
                            <span class="font-bold text-slate-700">{{ $eval->total_skor }}</span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($eval && $eval->rata_rata !== null)
                            <span class="font-black text-indigo-600 text-lg">{{ number_format($eval->rata_rata, 2) }}</span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-600">
                        @if($eval && $eval->rekomendasi)
                            <span class="line-clamp-2" title="{{ $eval->rekomendasi->rekomendasi }}">
                                {{ $eval->rekomendasi->rekomendasi }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="users" class="h-12 w-12 text-slate-200 mb-3"></i>
                            <p class="text-base font-medium text-slate-600">Tidak ada data guru</p>
                            <p class="text-sm mt-1">Tidak ada guru yang sesuai dengan pencarian Anda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($gurus->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $gurus->links() }}
    </div>
    @endif
</div>
@endif

@endsection
