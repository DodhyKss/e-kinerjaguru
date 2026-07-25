@extends('layouts.app')
@section('title', 'Periode Evaluasi')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Periode Evaluasi</h3>
        <a href="{{ route('evaluation-periods.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors inline-flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Periode
        </a>
    </div>
    
    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" action="{{ route('evaluation-periods.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end" id="searchForm">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Sekolah</label>
                <select name="school_id" class="select2-filter block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm" onchange="document.getElementById('searchForm').submit()">
                    <option value="">-- Semua Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cari Periode</label>
                <div class="flex gap-2">
                    <select name="search_id" class="select2-filter block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm" onchange="document.getElementById('searchForm').submit()">
                        <option value="">-- Semua Periode --</option>
                        @foreach($allData as $period)
                            <option value="{{ $period->id }}" {{ request('search_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->nama }} ({{ $period->tahun_ajaran }})
                            </option>
                        @endforeach
                    </select>
                    @if(request()->hasAny(['search_id', 'school_id']) && (request('search_id') || request('school_id')))
                    <a href="{{ route('evaluation-periods.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors flex items-center justify-center shrink-0" title="Reset">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
                <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama Periode</th>
                        <th class="px-6 py-4 font-semibold">Sekolah Tujuan</th>
                        <th class="px-6 py-4 font-semibold">Tahun Ajaran / Smt</th>
                        <th class="px-6 py-4 font-semibold">Jadwal Pelaksanaan</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                    <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">{{ $period->nama }}</td>
                        <td class="px-6 py-4">{{ $period->school->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium">{{ $period->tahun_ajaran }}</span>
                            <span class="text-xs text-slate-500 ml-1">({{ ucfirst($period->semester) }})</span>
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($period->tanggal_mulai)->format('d M Y') }} - <br>
                            {{ \Carbon\Carbon::parse($period->tanggal_selesai)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($period->status == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Sedang Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Selesai/Ditutup</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('evaluation-periods.edit', $period) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('evaluation-periods.destroy', $period) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">
                            Belum ada data periode evaluasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($periods->hasPages())
        <div class="mt-6">
            {{ $periods->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-filter').select2({
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
@endsection
