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

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6 p-6">
    <form method="GET" action="{{ route('kepala-sekolahs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Sekolah</label>
            <select name="school_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                <option value="">-- Semua Sekolah --</option>
                @foreach($schools as $sch)
                    <option value="{{ $sch->id }}" {{ request('school_id') == $sch->id ? 'selected' : '' }}>{{ $sch->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cari Spesifik Kepala Sekolah</label>
            <select name="user_id" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                <option value="">-- Pilih Nama Kepala Sekolah --</option>
                @foreach($allKepseks as $ak)
                    <option value="{{ $ak->id }}" {{ request('user_id') == $ak->id ? 'selected' : '' }}>{{ $ak->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center shadow-sm w-full justify-center">
                <i data-lucide="filter" class="w-4 h-4 mr-1.5"></i> Filter
            </button>
            @if(request()->hasAny(['school_id', 'user_id']) && (request('school_id') || request('user_id')))
            <a href="{{ route('kepala-sekolahs.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors flex items-center justify-center shrink-0" title="Reset">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            </a>
            @endif
        </div>
    </form>
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
                                <div class="font-bold text-slate-800">{{ $kepsek->name }}</div>
                                <div class="text-xs text-slate-500">{{ $kepsek->kepalaSekolah->nip ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-600">{{ $kepsek->kepalaSekolah->pangkatGolongan->nama ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm font-medium text-indigo-600">{{ $kepsek->school->nama ?? '-' }}</td>
                    <td class="py-4 px-6">
                        @if($kepsek->status === 'aktif')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('kepala-sekolahs.edit', $kepsek) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('kepala-sekolahs.destroy', $kepsek) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Kepala Sekolah ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
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

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
@endsection
