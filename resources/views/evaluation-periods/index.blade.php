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
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Nama Periode</th>
                        <th class="px-6 py-3">Sekolah Tujuan</th>
                        <th class="px-6 py-3">Tahun Ajaran / Smt</th>
                        <th class="px-6 py-3">Jadwal Pelaksanaan</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $period->nama }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $period->school->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium">{{ $period->tahun_ajaran }}</span>
                            <span class="text-xs text-slate-500 ml-1">({{ ucfirst($period->semester) }})</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-xs">
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
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
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
@endsection
