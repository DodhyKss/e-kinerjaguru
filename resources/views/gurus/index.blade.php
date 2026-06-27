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
    
    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" action="{{ route('gurus.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @if(auth()->user()->isAdmin())
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Sekolah</label>
                <select name="school_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                    <option value="">-- Semua Sekolah --</option>
                    @foreach($schools as $sch)
                        <option value="{{ $sch->id }}" {{ request('school_id') == $sch->id ? 'selected' : '' }}>{{ $sch->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                    <option value="">-- Semua Mapel --</option>
                    @foreach($mataPelajarans as $mp)
                        <option value="{{ $mp->id }}" {{ request('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>{{ $mp->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cari Spesifik Guru</label>
                <select name="guru_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                    <option value="">-- Pilih Nama Guru --</option>
                    @foreach($allGurus as $ag)
                        <option value="{{ $ag->id }}" {{ request('guru_id') == $ag->id ? 'selected' : '' }}>{{ $ag->nama }} ({{ $ag->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center shadow-sm w-full justify-center">
                    <i data-lucide="filter" class="w-4 h-4 mr-1.5"></i> Filter
                </button>
                @if(request()->hasAny(['school_id', 'mata_pelajaran_id', 'guru_id']) && (request('school_id') || request('mata_pelajaran_id') || request('guru_id')))
                <a href="{{ route('gurus.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors flex items-center justify-center shrink-0" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </form>
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
                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ $guru->nip }}
                            @if($guru->nuptk)
                            <span class="block text-xs text-slate-400">NUPTK: {{ $guru->nuptk }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-900">{{ $guru->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $guru->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $guru->mataPelajaran->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $guru->school->nama ?? '-' }}</td>
                        @if(auth()->user()->isAdmin())
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('gurus.edit', $guru) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('gurus.destroy', $guru) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
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

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endsection
