@extends('layouts.app')
@section('title', 'Ranking Kinerja Guru')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Peringkat Kinerja Guru</h2>
    <p class="text-sm text-slate-500 mt-1">Daftar peringkat berdasarkan perolehan nilai rata-rata evaluasi.</p>
</div>

<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-8 w-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900">Filter Peringkat</h3>
        </div>
        
        <form action="{{ route('reports.ranking') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="period_id" class="block text-xs font-medium text-slate-500 mb-1">Periode Evaluasi</label>
                <select name="period_id" id="period_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="">-- Semua Periode --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ $periodId == $period->id ? 'selected' : '' }}>
                            {{ $period->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(auth()->user()->isAdmin())
            <div>
                <label for="school_id" class="block text-xs font-medium text-slate-500 mb-1">Sekolah (Unit Kerja)</label>
                <select name="school_id" id="school_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="">-- Semua Sekolah --</option>
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
                <input type="text" name="guru_name" id="guru_name" value="{{ request('guru_name') }}" placeholder="Cari nama guru..." class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>

            <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                <a href="{{ route('reports.ranking') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                    Reset Filter
                </a>
                <button type="submit" class="px-4 py-2.5 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors inline-flex items-center shadow-sm">
                    <i data-lucide="award" class="w-4 h-4 mr-2"></i> Tampilkan Peringkat
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Peringkat</h3>
        <span class="text-sm text-slate-500">Menampilkan {{ $rankings->count() }} Guru</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-100/50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-center w-24">Peringkat</th>
                    <th class="px-6 py-4">Profil Guru</th>
                    <th class="px-6 py-4">Unit Kerja</th>
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4 text-center">Total Skor</th>
                    <th class="px-6 py-4 text-center">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankings as $index => $eval)
                    @php
                        $rank = $rankings->firstItem() + $index;
                        $isTop1 = $rank === 1;
                        $isTop2 = $rank === 2;
                        $isTop3 = $rank === 3;
                        
                        $rowBg = 'bg-white';
                        if ($isTop1) $rowBg = 'bg-amber-50/60 hover:bg-amber-50 border-l-4 border-l-amber-400';
                        elseif ($isTop2) $rowBg = 'bg-slate-50/60 hover:bg-slate-50 border-l-4 border-l-slate-300';
                        elseif ($isTop3) $rowBg = 'bg-orange-50/60 hover:bg-orange-50 border-l-4 border-l-orange-300';
                        else $rowBg = 'bg-white hover:bg-slate-50/80 border-l-4 border-l-transparent';
                    @endphp
                <tr class="border-b border-slate-50 transition-colors {{ $rowBg }}">
                    <td class="px-6 py-4 text-center font-bold text-lg">
                        @if($isTop1)
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="medal" class="w-8 h-8 text-amber-400 fill-amber-100 mb-1"></i>
                                <span class="text-amber-600 text-xs uppercase tracking-wider mt-1">#1</span>
                            </div>
                        @elseif($isTop2)
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="medal" class="w-7 h-7 text-slate-400 fill-slate-100 mb-1"></i>
                                <span class="text-slate-500 text-xs uppercase tracking-wider mt-1">#2</span>
                            </div>
                        @elseif($isTop3)
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="medal" class="w-6 h-6 text-orange-400 fill-orange-100 mb-1"></i>
                                <span class="text-orange-600 text-xs uppercase tracking-wider mt-1">#3</span>
                            </div>
                        @else
                            <span class="text-slate-400">#{{ $rank }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900 {{ $isTop1 ? 'text-lg' : '' }}">{{ $eval->guru->nama }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">NIP: {{ $eval->guru->nip ?? '-' }}</div>
                        <div class="text-xs text-slate-500">{{ $eval->guru->mata_pelajaran }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $eval->guru->school->nama ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $eval->evaluationPeriod->nama }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                            {{ $eval->total_skor }} pts
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-2xl font-black {{ $isTop1 ? 'text-amber-500' : ($isTop2 ? 'text-slate-500' : ($isTop3 ? 'text-orange-500' : 'text-indigo-600')) }}">
                                {{ number_format($eval->rata_rata, 2) }}
                            </span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-widest mt-1">Skala 4.0</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="award" class="h-12 w-12 text-slate-200 mb-3"></i>
                            <p class="text-base font-medium text-slate-600">Belum Ada Data Peringkat</p>
                            <p class="text-sm mt-1">Tidak ada evaluasi yang sudah disetujui / selesai untuk kriteria ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rankings->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $rankings->links() }}
    </div>
    @endif
</div>
@endsection
