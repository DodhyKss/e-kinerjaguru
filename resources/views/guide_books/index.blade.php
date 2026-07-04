@extends('layouts.app')
@section('title', 'Master Buku Panduan PDF')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Manajemen Buku Panduan (PDF)</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola file buku panduan yang dapat diunduh oleh guru dan asesor di halaman login & panduan.</p>
    </div>
    <a href="{{ route('guide-books.create') }}" class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-all flex items-center shadow-sm shadow-indigo-200 shrink-0">
        <i data-lucide="upload-cloud" class="w-4 h-4 mr-2"></i> Unggah Panduan PDF
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 w-16">No</th>
                    <th class="px-6 py-4">Judul Panduan</th>
                    <th class="px-6 py-4">Nama File Asli</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Tanggal Unggah</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($guideBooks as $index => $item)
                <tr class="hover:bg-slate-50/80 transition-colors {{ $item->is_active ? 'bg-indigo-50/30' : '' }}">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-rose-50 text-rose-600 rounded-lg shrink-0">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <span>{{ $item->judul }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        <span class="inline-flex items-center text-xs font-mono bg-slate-100 px-2.5 py-1 rounded-md text-slate-700 border border-slate-200">
                            {{ $item->original_filename }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->is_active)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span> Aktif Diunduh
                        </span>
                        @else
                        <form action="{{ route('guide-books.toggle-active', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 hover:bg-indigo-100 hover:text-indigo-700 border border-slate-200 transition-colors" title="Jadikan Panduan Aktif">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Set Aktif
                            </button>
                        </form>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs">
                        {{ $item->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="{{ route('guide-books.download', $item) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium text-xs rounded-lg transition-colors border border-indigo-100" title="Download PDF">
                                <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5"></i> Download
                            </a>
                            <form action="{{ route('guide-books.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file panduan ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <i data-lucide="file-x" class="w-6 h-6"></i>
                            </div>
                            <p class="font-medium text-slate-700">Belum Ada Buku Panduan</p>
                            <p class="text-xs text-slate-400 mt-1">Silakan klik tombol "Unggah Panduan PDF" untuk menambahkan file pertama.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
