@extends('layouts.app')
@section('title', 'Data Asesor / Penilai')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Asesor Kinerja</h3>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('penilais.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors inline-flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Asesor
        </a>
        @endif
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Nama Asesor</th>
                        <th class="px-6 py-3">NIP / ID</th>
                        <th class="px-6 py-3">Jabatan & Instansi</th>
                        <th class="px-6 py-3">Penugasan Sekolah</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penilais as $penilai)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-900">{{ $penilai->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $penilai->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $penilai->nip ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="text-slate-800">{{ $penilai->jabatan }}</div>
                            <div class="text-xs text-slate-500">{{ $penilai->instansi }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $penilai->school->nama }}</td>
                        
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('penilais.edit', $penilai) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('penilais.destroy', $penilai) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Asesor ini? Akun login juga akan dihapus.');">
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
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data asesor/penilai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($penilais->hasPages())
        <div class="mt-6">
            {{ $penilais->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
