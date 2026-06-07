@extends('layouts.app')
@section('title', 'Data Guru')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Guru</h3>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('gurus.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors inline-flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Guru
        </a>
        @endif
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">NIP / NUPTK</th>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3">Mata Pelajaran</th>
                        <th class="px-6 py-3">Asal Sekolah</th>
                        @if(auth()->user()->isAdmin())
                        <th class="px-6 py-3 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $guru)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $guru->nip }}</div>
                            <div class="text-xs text-slate-500">{{ $guru->nuptk ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-900">{{ $guru->nama }}</div>
                            <div class="text-xs text-slate-600">{{ $guru->pangkatGolongan->nama ?? '' }} - {{ $guru->jabatanFungsional->nama ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-800">{{ $guru->mataPelajaran->nama ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $guru->kompetensiKeahlian->nama ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $guru->school->nama }}</td>
                        
                        @if(auth()->user()->isAdmin())
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('gurus.edit', $guru) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('gurus.destroy', $guru) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Guru ini? Akun login juga akan dihapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data guru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($gurus->hasPages())
        <div class="mt-6">
            {{ $gurus->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
