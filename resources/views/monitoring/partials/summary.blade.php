{{-- Kartu ringkasan agregat wilayah. $totals & $level digunakan bersama. --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Sekolah</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totals['sekolah'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Guru</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totals['guru'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Asesor</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totals['asesor'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Evaluasi</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totals['evaluasi'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Evaluasi Selesai</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($totals['selesai'], 0, ',', '.') }}</p>
        @if($totals['evaluasi'] > 0)
            <p class="text-[11px] text-slate-500 mt-1">{{ round($totals['selesai'] / $totals['evaluasi'] * 100, 1) }}% dari total</p>
        @endif
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rata-rata Nilai</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $totals['rata_rata'] ?? '-' }}</p>
    </div>
</div>