@extends('layouts.app')
@section('title', 'Master Jenis Dokumen')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Master Jenis Dokumen</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola jenis dokumen bukti dan pemetaannya ke aspek penilaian.</p>
    </div>
    <a href="{{ route('jenis-dokumens.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm inline-flex items-center">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Jenis Dokumen
    </a>
</div>

@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center">
    <i data-lucide="check-circle-2" class="w-5 h-5 mr-2"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
</div>
@endif

<div class="mb-6 p-5 bg-white rounded-2xl shadow-sm border border-slate-200">
    <form action="{{ route('jenis-dokumens.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end" id="searchForm">
        <div class="w-full sm:w-1/2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Cari / Filter Data</label>
            <select name="search_id" class="select2-filter w-full" onchange="document.getElementById('searchForm').submit()">
                <option value="">Semua Data...</option>
                @foreach($allData as $item)
                    <option value="{{ $item->id }}" {{ request('search_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_jenis_dokumen }}
                    </option>
                @endforeach
            </select>
        </div>
        @if(request('search_id'))
            <a href="{{ route('jenis-dokumens.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors">
                Reset Filter
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <th class="px-6 py-4 font-semibold">No</th>
                    <th class="px-6 py-4 font-semibold">Nama Jenis Dokumen</th>
                    <th class="px-6 py-4 font-semibold">Jumlah Aspek Terpetakan</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($jenisDokumens as $item)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">{{ $loop->iteration + $jenisDokumens->firstItem() - 1 }}</td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-slate-900">{{ $item->nama_jenis_dokumen }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold {{ $item->assessment_aspects_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $item->assessment_aspects_count }} Aspek
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('jenis-dokumens.edit', $item) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('jenis-dokumens.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis dokumen ini? Pemetaan pada aspek terkait akan direset.');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center">Belum ada data jenis dokumen.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($jenisDokumens->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $jenisDokumens->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-filter').select2({
            placeholder: "Cari data...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
@endsection
