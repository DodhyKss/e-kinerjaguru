@extends('layouts.app')
@section('title', 'Master Provinsi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Master Data Provinsi</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola data provinsi untuk referensi sekolah.</p>
    </div>
    <a href="{{ route('provinsis.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center shadow-sm">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Provinsi
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nama Provinsi</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($provinsis as $provinsi)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $provinsi->id }}</td>
                    <td class="px-6 py-4">{{ $provinsi->nama }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('provinsis.edit', $provinsi) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('provinsis.destroy', $provinsi) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Kabupaten yang terkait mungkin akan ikut terhapus.');">
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
                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="map" class="w-8 h-8 text-slate-300 mb-2"></i>
                            <p>Belum ada data provinsi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($provinsis->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $provinsis->links() }}
    </div>
    @endif
</div>
@endsection
