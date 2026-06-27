@extends('layouts.app')
@section('title', 'Data Asesor / Evaluator')

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
    
    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" action="{{ route('penilais.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
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
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cari Spesifik Asesor</label>
                <select name="penilai_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                    <option value="">-- Pilih Nama Asesor --</option>
                    @foreach($allPenilais as $ap)
                        <option value="{{ $ap->id }}" {{ request('penilai_id') == $ap->id ? 'selected' : '' }}>{{ $ap->nama }} ({{ $ap->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center shadow-sm w-full justify-center">
                    <i data-lucide="filter" class="w-4 h-4 mr-1.5"></i> Filter
                </button>
                @if(request()->hasAny(['school_id', 'penilai_id']) && (request('school_id') || request('penilai_id')))
                <a href="{{ route('penilais.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors flex items-center justify-center shrink-0" title="Reset">
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
                        <th class="px-6 py-3">Nama Asesor</th>
                        <th class="px-6 py-3">NIP / ID</th>
                        <th class="px-6 py-3">Jabatan & Instansi</th>
                        <th class="px-6 py-3">Penugasan Sekolah</th>
                        <th class="px-6 py-3">Guru Yang Dinilai</th>
                        @if(auth()->user()->isAdmin())
                        <th class="px-6 py-3 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($penilais as $penilai)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-900">{{ $penilai->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $penilai->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $penilai->nip ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="text-slate-800">{{ $penilai->jabatan }}</div>
                            <div class="text-xs text-slate-500">{{ $penilai->instansi }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $penilai->school->nama ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($penilai->gurus && $penilai->gurus->count() > 0)
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach($penilai->gurus as $assignedGuru)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $assignedGuru->nama }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs italic text-slate-400">Belum ada guru ditugaskan</span>
                            @endif
                        </td>
                        
                        @if(auth()->user()->isAdmin())
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('penilais.edit', $penilai) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('penilais.destroy', $penilai) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data asesor ini? Akun login juga akan dihapus.');">
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
                        <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="px-6 py-8 text-center text-slate-500">
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

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endsection
