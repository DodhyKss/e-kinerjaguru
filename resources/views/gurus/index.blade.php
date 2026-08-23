@extends('layouts.app')
@section('title', 'Data Guru')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Guru</h3>
        @if(auth()->user()->isAdmin())
        <div class="flex gap-2">
            <button type="button" onclick="document.getElementById('modalTambahDariAsesor').classList.remove('hidden')" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors inline-flex items-center">
                <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i> Tambah dari Asesor
            </button>
            <a href="{{ route('gurus.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors inline-flex items-center">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Guru Baru
            </a>
        </div>
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
            <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
                <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">NIP / NUPTK</th>
                        <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                        <th class="px-6 py-4 font-semibold">Mata Pelajaran</th>
                        <th class="px-6 py-4 font-semibold">Asal Sekolah</th>
                        @if(auth()->user()->isAdmin())
                        <th class="px-6 py-4 font-semibold">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $guru)
                    <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium">
                            {{ $guru->nip }}
                            @if($guru->nuptk)
                            <span class="block text-xs text-slate-400">NUPTK: {{ $guru->nuptk }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-indigo-900">{{ $guru->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $guru->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $guru->mataPelajaran->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $guru->school->nama ?? '-' }}</td>
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
                        <td colspan="5" class="px-6 py-4 text-center">
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

<!-- Modal Tambah dari Asesor -->
<div id="modalTambahDariAsesor" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalTambahDariAsesor').classList.add('hidden')"></div>
        <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
            <form action="{{ route('gurus.createFromPenilai') }}" method="GET">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                Tambah Guru dari Data Asesor
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-slate-500 mb-4">
                                    Pilih Asesor yang ingin dijadikan Guru. Form pembuatan akan terbuka otomatis terisi dengan data mereka.
                                </p>
                                <select name="penilai_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm p-2.5 border select2" required>
                                    <option value="">-- Pilih Asesor --</option>
                                    @forelse($penilaisBelumGuru as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->instansi ?? '-' }})</option>
                                    @empty
                                        <option value="" disabled>Semua Asesor sudah terdaftar sebagai Guru</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Lanjut ke Form
                    </button>
                    <button type="button" onclick="document.getElementById('modalTambahDariAsesor').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
