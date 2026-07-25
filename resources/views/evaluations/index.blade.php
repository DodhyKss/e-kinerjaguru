@extends('layouts.app')
@section('title', 'Data Evaluasi Kinerja')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="text-lg font-medium text-slate-900">Daftar Evaluasi</h3>
        @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
            <a href="{{ route('evaluations.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors inline-flex items-center">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Buat Penugasan Baru
            </a>
        @endif
    </div>

    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" action="{{ route('evaluations.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 items-end" id="searchForm">
            @if(auth()->user()->isAdmin())
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Sekolah</label>
                <select name="school_id" class="select2-filter block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm" onchange="document.getElementById('searchForm').submit()">
                    <option value="">-- Semua Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Periode</label>
                <select name="period_id" class="select2-filter block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm" onchange="document.getElementById('searchForm').submit()">
                    <option value="">-- Semua Periode --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                            {{ $period->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(!auth()->user()->isGuru())
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Guru</label>
                <select name="guru_id" class="select2-filter block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm" onchange="document.getElementById('searchForm').submit()">
                    <option value="">-- Semua Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Asesor</label>
                <select name="penilai_id" class="select2-filter block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm" onchange="document.getElementById('searchForm').submit()">
                    <option value="">-- Semua Asesor --</option>
                    @foreach($penilais as $penilai)
                        <option value="{{ $penilai->id }}" {{ request('penilai_id') == $penilai->id ? 'selected' : '' }}>
                            {{ $penilai->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Filter Status</label>
                <select name="status" class="select2-filter block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm" onchange="document.getElementById('searchForm').submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dimulai</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Proses Penilaian</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                @if(request()->hasAny(['period_id', 'guru_id', 'penilai_id', 'status', 'school_id']) && (request('period_id') || request('guru_id') || request('penilai_id') || request('status') || request('school_id')))
                <a href="{{ route('evaluations.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-300 transition-colors flex items-center justify-center shrink-0" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i> Reset Filter
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Periode</th>
                    <th class="px-6 py-4 font-semibold">Guru Yang Dinilai</th>
                    <th class="px-6 py-4 font-semibold">Asesor/Penilai</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Skor (Rata-rata)</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eval)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $eval->evaluationPeriod->nama }}</td>
                    <td class="px-6 py-4">
                        @if(auth()->user()->guru && $eval->guru_id === auth()->user()->guru->id)
                            <div class="font-bold text-indigo-600 bg-indigo-50 inline-block px-2 py-0.5 rounded text-xs mb-1">Anda</div>
                        @else
                            <div class="font-medium text-slate-900">{{ $eval->guru->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $eval->guru->mata_pelajaran }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if(auth()->user()->penilai && $eval->penilai_id === auth()->user()->penilai->id)
                            <span class="text-indigo-600 font-bold bg-indigo-50 inline-block px-2 py-0.5 rounded text-xs">Anda</span>
                        @else
                            {{ $eval->penilai->nama }}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($eval->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Disetujui</span>
                        @elseif($eval->status == 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Menunggu Review</span>
                        @elseif($eval->status == 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Proses Penilaian</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Belum Dimulai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($eval->rata_rata)
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-indigo-600">{{ $eval->rata_rata }}</span>
                                <span class="text-xs text-slate-400">/ 4.0</span>
                            </div>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('evaluations.show', $eval) }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                <i data-lucide="eye" class="w-4 h-4 mr-1.5"></i> Detail
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
                                <a href="{{ route('evaluations.edit', $eval) }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1.5"></i> Edit
                                </a>
                                <form action="{{ route('evaluations.destroy', $eval) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penugasan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-sm font-medium text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1.5"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="inbox" class="h-10 w-10 text-slate-300 mb-3"></i>
                            <p>Belum ada data evaluasi yang ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($evaluations->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $evaluations->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-filter').select2({
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush

@endsection
