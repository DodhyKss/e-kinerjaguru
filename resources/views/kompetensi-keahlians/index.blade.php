
@extends('layouts.app')
@section('title', 'Master Kompetensi Keahlian')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Master Data Kompetensi Keahlian</h2>
    </div>
    <a href="{{ route('kompetensi-keahlians.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center shadow-sm">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Data
    </a>
</div>


<div class="mb-6 p-5 bg-white rounded-2xl shadow-sm border border-slate-200">
    <form action="{{ route('kompetensi-keahlians.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end" id="searchForm">
        <div class="w-full sm:w-1/2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Cari / Filter Data</label>
            <select name="search_id" class="select2-filter w-full" onchange="document.getElementById('searchForm').submit()">
                <option value="">Semua Data...</option>
                @foreach($allData as $item)
                    <option value="{{ $item->id }}" {{ request('search_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        @if(request('search_id'))
            <a href="{{ route('kompetensi-keahlians.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors">
                Reset Filter
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">Nama Kompetensi Keahlian</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($data as $item)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $item->id }}</td>
                    <td class="px-6 py-4">{{ $item->nama }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('kompetensi-keahlians.edit', $item) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('kompetensi-keahlians.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($data->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $data->links() }}
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
