@extends('layouts.app')
@section('title', 'Data Sekolah')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Sekolah</h3>
        <a href="{{ route('schools.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors inline-flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Sekolah
        </a>
    </div>
    
    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" action="{{ route('schools.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Kabupaten / Kota</label>
                <select name="kabupaten_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                    <option value="">-- Semua Kabupaten / Kota --</option>
                    @foreach($kabupatens as $kab)
                        <option value="{{ $kab->id }}" {{ request('kabupaten_id') == $kab->id ? 'selected' : '' }}>{{ $kab->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cari Spesifik Sekolah</label>
                <select name="school_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                    <option value="">-- Pilih Nama Sekolah --</option>
                    @foreach($allSchools as $asch)
                        <option value="{{ $asch->id }}" {{ request('school_id') == $asch->id ? 'selected' : '' }}>{{ $asch->nama }} ({{ $asch->npsn }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center shadow-sm w-full justify-center">
                    <i data-lucide="filter" class="w-4 h-4 mr-1.5"></i> Filter
                </button>
                @if(request()->hasAny(['kabupaten_id', 'school_id']) && (request('kabupaten_id') || request('school_id')))
                <a href="{{ route('schools.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors flex items-center justify-center shrink-0" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
                <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">NPSN</th>
                        <th class="px-6 py-4 font-semibold">Nama Sekolah</th>
                        <th class="px-6 py-4 font-semibold">Kepala Sekolah</th>
                        <th class="px-6 py-4 font-semibold">Kab/Kota</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $school)
                    <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium">{{ $school->npsn }}</td>
                        <td class="px-6 py-4">{{ $school->nama }}</td>
                        <td class="px-6 py-4">{{ $school->kepala_sekolah ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $school->kabupaten->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($school->status == 'aktif')
                                <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-semibold border border-emerald-200">Aktif</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-semibold border border-slate-200">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('schools.edit', $school) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sekolah ini? Semua data terkait (Guru, Penilai, Evaluasi) juga akan terpengaruh.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">
                            Belum ada data sekolah.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($schools->hasPages())
        <div class="mt-6">
            {{ $schools->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endsection
