@extends('layouts.app')
@section('title', 'Daftar Evaluasi (Rekomendasi)')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Daftar Rekomendasi Evaluasi</h2>
        <p class="text-sm text-slate-500 mt-1">Berikan rekomendasi untuk evaluasi guru yang telah selesai dinilai.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
            <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Periode</th>
                    <th class="px-6 py-4 font-semibold">Guru yang Dinilai</th>
                    <th class="px-6 py-4 font-semibold">Penilai</th>
                    <th class="px-6 py-4 font-semibold">Skor Akhir</th>
                    <th class="px-6 py-4 font-semibold">Status Rekomendasi</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($evaluations as $item)
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $item->evaluationPeriod->nama }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $item->guru->nama }}</div>
                        <div class="text-xs text-slate-500">NIP. {{ $item->guru->nip }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $item->penilai->nama }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            {{ $item->rata_rata }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->rekomendasi)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                Sudah Diberikan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                Belum Diberikan
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('evaluations.rekomendasis.create', $item) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors border border-indigo-100">
                            @if($item->rekomendasi)
                                <i data-lucide="edit-2" class="w-4 h-4 mr-1.5"></i> Edit
                            @else
                                <i data-lucide="plus-circle" class="w-4 h-4 mr-1.5"></i> Input
                            @endif
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center">
                            <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mb-3"></i>
                            <p>Belum ada evaluasi yang selesai untuk diberikan rekomendasi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($evaluations->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $evaluations->links() }}
    </div>
    @endif
</div>
@endsection
