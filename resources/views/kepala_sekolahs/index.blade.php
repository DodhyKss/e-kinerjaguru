@extends('layouts.app')
@section('title', 'Master Kepala Sekolah')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Master Kepala Sekolah</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola data profil dan akun Kepala Sekolah.</p>
    </div>
    <a href="{{ route('kepala-sekolahs.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors whitespace-nowrap">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Kepala Sekolah
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                    <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama & NIP</th>
                    <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Pangkat / Gol.</th>
                    <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Unit Kerja (Sekolah)</th>
                    <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($kepalaSekolahs as $index => $kepsek)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6 text-sm text-slate-600">{{ $kepalaSekolahs->firstItem() + $index }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                {{ $kepsek->initials }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $kepsek->name }}</p>
                                <p class="text-xs text-slate-500">NIP: {{ $kepsek->kepalaSekolah->nip ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-600">
                        {{ $kepsek->kepalaSekolah->pangkatGolongan->nama ?? '-' }}
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-600">
                        {{ $kepsek->school->nama ?? '-' }}
                    </td>
                    <td class="py-4 px-6">
                        @if($kepsek->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 border border-rose-200">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('kepala-sekolahs.edit', $kepsek) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('kepala-sekolahs.destroy', $kepsek) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Kepala Sekolah ini?');">
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
                    <td colspan="6" class="py-8 px-6 text-center text-slate-500">Belum ada data Kepala Sekolah.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($kepalaSekolahs->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $kepalaSekolahs->links() }}
    </div>
    @endif
</div>
@endsection
