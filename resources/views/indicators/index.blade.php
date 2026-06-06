@extends('layouts.app')
@section('title', 'Master Data Indikator')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Indikator Penilaian</h3>
        <a href="{{ route('indicators.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors inline-flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Indikator Baru
        </a>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Dimensi & Nama Indikator</th>
                        <th class="px-6 py-3">Metode Penilaian</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indicators as $indicator)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ $indicator->kode }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-indigo-600 font-semibold mb-1">{{ $indicator->dimension->nama }}</div>
                            <div class="font-bold text-slate-900">{{ $indicator->nama }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                @if($indicator->has_observasi) <span class="text-xs text-slate-600"><i data-lucide="check" class="w-3 h-3 inline text-emerald-500"></i> Observasi</span> @endif
                                @if($indicator->has_telaah_dokumen) <span class="text-xs text-slate-600"><i data-lucide="check" class="w-3 h-3 inline text-emerald-500"></i> Telaah Dokumen</span> @endif
                                @if($indicator->has_wawancara) <span class="text-xs text-slate-600"><i data-lucide="check" class="w-3 h-3 inline text-emerald-500"></i> Wawancara</span> @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('indicators.show', $indicator) }}" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors font-medium text-xs flex items-center" title="Kelola Komponen (Level & Aspek)">
                                    <i data-lucide="settings-2" class="w-4 h-4 mr-1"></i> Kelola Aspek & Level
                                </a>
                                <a href="{{ route('indicators.edit', $indicator) }}" class="text-slate-600 hover:bg-slate-100 p-1.5 rounded-lg transition-colors border border-slate-200" title="Edit Data Dasar">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('indicators.destroy', $indicator) }}" method="POST" class="inline-block" onsubmit="return confirm('Peringatan: Menghapus indikator akan menghapus seluruh data Level Capaian dan Aspek Penilaian di dalamnya. Apakah Anda yakin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors border border-rose-100" title="Hapus Indikator">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data indikator.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($indicators->hasPages())
        <div class="mt-6">
            {{ $indicators->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
