@extends('layouts.app')
@section('title', 'Detail Indikator')

@section('content')
<div class="mb-6">
    <a href="{{ route('indicators.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Indikator
    </a>
</div>

<!-- Header Info Indikator -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="p-8">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $indicator->kode }}</span>
                    <span class="text-sm font-medium text-slate-500">{{ $indicator->dimension->nama }}</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $indicator->nama }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ $indicator->deskripsi }}</p>
            </div>
            <a href="{{ route('indicators.edit', $indicator) }}" class="flex-shrink-0 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors border border-indigo-100">
                Edit Identitas Dasar
            </a>
        </div>
    </div>
    <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 flex gap-6">
        <div class="text-sm">
            <span class="text-slate-500">Metode Observasi:</span>
            <span class="font-semibold text-slate-800 ml-1">{{ $indicator->has_observasi ? 'Tersedia' : 'Tidak' }}</span>
        </div>
        <div class="text-sm">
            <span class="text-slate-500">Telaah Dokumen:</span>
            <span class="font-semibold text-slate-800 ml-1">{{ $indicator->has_telaah_dokumen ? 'Tersedia' : 'Tidak' }}</span>
        </div>
        <div class="text-sm">
            <span class="text-slate-500">Wawancara:</span>
            <span class="font-semibold text-slate-800 ml-1">{{ $indicator->has_wawancara ? 'Tersedia' : 'Tidak' }}</span>
        </div>
    </div>
</div>

<!-- Grid Layout: Level Capaian & Aspek -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Kolom Kiri: Level Capaian -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-emerald-50/50">
                <h3 class="text-lg font-bold text-slate-900 flex items-center">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-emerald-600 mr-2"></i> Deskripsi Level Capaian
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <form action="{{ route('indicators.levels.bulk', ['indicator' => $indicator->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        @foreach($indicator->achievementLevels as $level)
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-sm">
                                    L{{ $level->level }}
                                </span>
                            </div>
                            <textarea name="levels[{{ $level->id }}][deskripsi]" rows="3" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-2.5" required>{{ $level->deskripsi }}</textarea>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 text-right">
                        <button type="submit" class="text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-5 py-2 rounded-xl transition-colors shadow-sm">Simpan Semua Level</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aspek Penilaian -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-indigo-50/50">
                <h3 class="text-lg font-bold text-slate-900 flex items-center">
                    <i data-lucide="list-checks" class="w-5 h-5 text-indigo-600 mr-2"></i> Aspek Penilaian
                </h3>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="border-b border-slate-200 px-6 pt-4 flex gap-4">
                <button onclick="switchTab('observasi')" id="tab-btn-observasi" class="px-4 py-2 text-sm font-bold border-b-2 transition-colors border-indigo-600 text-indigo-600">Observasi</button>
                <button onclick="switchTab('dokumen')" id="tab-btn-dokumen" class="px-4 py-2 text-sm font-bold border-b-2 transition-colors border-transparent text-slate-500 hover:text-slate-700">Telaah Dokumen</button>
                <button onclick="switchTab('wawancara')" id="tab-btn-wawancara" class="px-4 py-2 text-sm font-bold border-b-2 transition-colors border-transparent text-slate-500 hover:text-slate-700">Wawancara</button>
            </div>

            <div class="p-6">
                <!-- Tab: Observasi -->
                <div id="tab-observasi" class="tab-content">
                    @if(!$indicator->has_observasi)
                        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl text-sm border border-rose-100 mb-6">
                            Indikator ini diatur untuk <b>tidak memiliki</b> metode observasi.
                        </div>
                    @else
                        @include('indicators.partials.aspect_form', ['metode' => 'observasi', 'metodeLabel' => 'Observasi', 'aspects' => $indicator->observationAspects])
                    @endif
                </div>

                <!-- Tab: Dokumen -->
                <div id="tab-dokumen" class="tab-content hidden">
                    @if(!$indicator->has_telaah_dokumen)
                        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl text-sm border border-rose-100 mb-6">
                            Indikator ini diatur untuk <b>tidak memiliki</b> metode telaah dokumen.
                        </div>
                    @else
                        @include('indicators.partials.aspect_form', ['metode' => 'telaah_dokumen', 'metodeLabel' => 'Telaah Dokumen', 'aspects' => $indicator->documentReviewAspects])
                    @endif
                </div>

                <!-- Tab: Wawancara -->
                <div id="tab-wawancara" class="tab-content hidden">
                    @if(!$indicator->has_wawancara)
                        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl text-sm border border-rose-100 mb-6">
                            Indikator ini diatur untuk <b>tidak memiliki</b> metode wawancara.
                        </div>
                    @else
                        @include('indicators.partials.aspect_form', ['metode' => 'wawancara', 'metodeLabel' => 'Wawancara', 'aspects' => $indicator->interviewAspects])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Sembunyikan semua konten tab
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Reset styling semua tombol
        ['observasi', 'dokumen', 'wawancara'].forEach(id => {
            const btn = document.getElementById('tab-btn-' + id);
            btn.classList.remove('border-indigo-600', 'text-indigo-600');
            btn.classList.add('border-transparent', 'text-slate-500');
        });
        
        // Tampilkan konten yang dipilih
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        
        // Update styling tombol aktif
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-500');
        activeBtn.classList.add('border-indigo-600', 'text-indigo-600');
    }
</script>
@endsection
