@extends('layouts.app')
@section('title', 'Monitoring - Sekolah ' . $kabupaten->nama)

@section('content')
@php $provinsiRef = $kabupaten->provinsi; @endphp

<nav class="flex items-center text-sm text-slate-500 mb-4 flex-wrap">
    <a href="{{ route('monitoring.index', request()->only(['period_id','status'])) }}" class="hover:text-indigo-600 font-medium">Kementerian</a>
    <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-slate-300"></i>
    @if($provinsiRef)
        <a href="{{ route('monitoring.kabupaten', array_merge(['provinsi' => $provinsiRef->id], request()->only(['period_id','status']))) }}" class="hover:text-indigo-600 font-medium">{{ $provinsiRef->nama }}</a>
        <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-slate-300"></i>
    @endif
    <span class="font-semibold text-slate-700">{{ $kabupaten->nama }}</span>
</nav>

<div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Rincian Sekolah</h3>
        <p class="text-sm text-slate-500 mt-1">Kabupaten {{ $kabupaten->nama }} &mdash; sekolah dengan evaluasi 0% berarti belum ada penilaian sama sekali.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('monitoring.export', array_merge(request()->query(), ['level' => 'sekolah', 'kabupaten' => $kabupaten->id])) }}"
            class="inline-flex items-center px-4 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 rounded-xl transition-colors">
            <i data-lucide="download" class="w-4 h-4 mr-1.5"></i> Unduh CSV
        </a>
        <a href="{{ route('monitoring.print', array_merge(request()->query(), ['level' => 'sekolah', 'kabupaten' => $kabupaten->id])) }}" target="_blank"
            class="inline-flex items-center px-4 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors">
            <i data-lucide="printer" class="w-4 h-4 mr-1.5"></i> Cetak
        </a>
    </div>
</div>

<form method="GET" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Periode Evaluasi</label>
            <select name="period_id" id="monitoring-periode" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                <option value="">Semua Periode</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ (int) request('period_id') === $period->id ? 'selected' : '' }}>
                        {{ $period->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Status Evaluasi</label>
            <select name="status" id="monitoring-status" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft / Belum Dimulai</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Proses Penilaian</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Menunggu Review Kepala Sekolah</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui Kepala Sekolah</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                <i data-lucide="filter" class="w-4 h-4 mr-1.5"></i> Terapkan
            </button>
            <a href="{{ route('monitoring.sekolah', $kabupaten) }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Reset</a>
        </div>
    </div>
</form>

@include('monitoring.partials.summary')

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100">
        <h4 class="text-base font-bold text-slate-900">Sekolah di {{ $kabupaten->nama }}</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Sekolah</th>
                    <th class="px-6 py-4 font-semibold">NPSN</th>
                    <th class="px-6 py-4 font-semibold">Guru</th>
                    <th class="px-6 py-4 font-semibold">Asesor</th>
                    <th class="px-6 py-4 font-semibold">Evaluasi</th>
                    <th class="px-6 py-4 font-semibold">Selesai</th>
                    <th class="px-6 py-4 font-semibold">Rata-rata</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $row['nama'] }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $row['npsn'] ?? '-' }}</td>
                    <td class="px-6 py-4">{{ number_format($row['guru'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4">{{ number_format($row['asesor'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4">{{ number_format($row['evaluasi'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-emerald-600">{{ number_format($row['selesai'], 0, ',', '.') }}</span>
                        @if($row['evaluasi'] > 0)
                            <span class="text-xs text-slate-400 ml-1">({{ round($row['selesai'] / $row['evaluasi'] * 100, 1) }}%)</span>
                        @else
                            <span class="text-xs text-rose-500 ml-1">(belum dinilai)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-indigo-600">{{ $row['rata_rata'] ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('reports.recap', array_merge(['school_id' => $row['id']], request()->only(['period_id']))) }}"
                            class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-900">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 mr-1"></i> Rekapitulasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">Belum ada sekolah pada kabupaten ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#monitoring-periode, #monitoring-status').select2({ width: '100%' });
    });
</script>
@endpush