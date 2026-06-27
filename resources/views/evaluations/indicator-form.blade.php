@extends('layouts.app')
@section('title', 'Penilaian Indikator: ' . $indicator->kode)

@section('content')
<div class="mb-6">
    <a href="{{ route('evaluations.show', $evaluation) }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Rincian Evaluasi
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="px-8 py-6 border-b border-slate-100 bg-indigo-50/50">
        <div class="flex items-center gap-2 mb-3">
            <span class="inline-flex px-2.5 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">{{ $indicator->kode }}</span>
            <span class="text-sm font-medium text-slate-500">{{ $indicator->dimension->nama }}</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $indicator->nama }}</h2>
        <p class="text-sm text-slate-600 leading-relaxed max-w-4xl">{{ $indicator->deskripsi }}</p>
    </div>
</div>

<form action="{{ route('evaluations.indicator.save', [$evaluation, $indicator]) }}" method="POST" id="evaluation-form">
    @csrf

    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-slate-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
            @if($indicator->has_observasi)
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 rounded-t-lg hover:text-slate-600 hover:border-slate-300" id="observasi-tab" data-tabs-target="#observasi" type="button" role="tab" aria-controls="observasi" aria-selected="false">
                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Hasil Observasi
                </button>
            </li>
            @endif
            @if($indicator->has_telaah_dokumen)
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 rounded-t-lg hover:text-slate-600 hover:border-slate-300" id="dokumen-tab" data-tabs-target="#dokumen" type="button" role="tab" aria-controls="dokumen" aria-selected="false">
                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Telaah Dokumen
                </button>
            </li>
            @endif
            @if($indicator->has_wawancara)
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 rounded-t-lg hover:text-slate-600 hover:border-slate-300" id="wawancara-tab" data-tabs-target="#wawancara" type="button" role="tab" aria-controls="wawancara" aria-selected="false">
                    <i data-lucide="mic" class="w-4 h-4 mr-2"></i> Wawancara
                </button>
            </li>
            @endif
            <li class="mr-2 ml-auto" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-indigo-600 text-indigo-600 rounded-t-lg font-bold" id="kesimpulan-tab" data-tabs-target="#kesimpulan" type="button" role="tab" aria-controls="kesimpulan" aria-selected="true">
                    <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> Kesimpulan & Level
                </button>
            </li>
        </ul>
    </div>

    <!-- Tabs Content -->
    <div id="myTabContent" class="mb-8">
        
        @if($indicator->has_observasi)
        <!-- Observasi Tab -->
        <div class="hidden bg-white rounded-2xl shadow-sm border border-slate-200 p-8" id="observasi" role="tabpanel" aria-labelledby="observasi-tab">
            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center"><i data-lucide="eye" class="w-5 h-5 mr-2 text-indigo-600"></i> Tabel Pengumpulan Data Observasi</h3>
            
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border border-slate-200">
                        <tr>
                            <th class="px-4 py-3 border-r border-slate-200 w-12 text-center">No</th>
                            <th class="px-4 py-3 border-r border-slate-200 w-1/2">Aspek yang Diobservasi</th>
                            <th class="px-4 py-3">Hasil Observasi (Faktual)</th>
                        </tr>
                    </thead>
                    <tbody class="border border-slate-200">
                        @foreach($indicator->observationAspects as $aspect)
                        @php $val = $result->observationData->where('assessment_aspect_id', $aspect->id)->first()?->hasil ?? ''; @endphp
                        <tr class="border-b border-slate-200">
                            <td class="px-4 py-3 border-r border-slate-200 text-center text-slate-500">{{ $aspect->nomor }}</td>
                            <td class="px-4 py-3 border-r border-slate-200 text-slate-700">{{ $aspect->aspek }}</td>
                            <td class="px-0 py-0 relative">
                                <textarea name="observation[{{ $aspect->id }}]" rows="2" {{ isset($isReadOnly) && $isReadOnly ? 'readonly disabled' : '' }} class="w-full h-full min-h-[60px] border-0 focus:ring-2 focus:ring-inset focus:ring-indigo-500 resize-y p-3 bg-transparent" placeholder="Catat hasil observasi...">{{ $val }}</textarea>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan Observasi</label>
                <textarea name="observation_note" rows="3" {{ isset($isReadOnly) && $isReadOnly ? 'readonly disabled' : '' }} class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">{{ $result->observationNote->catatan ?? '' }}</textarea>
            </div>
        </div>
        @endif

        @if($indicator->has_telaah_dokumen)
        <!-- Telaah Dokumen Tab -->
        <div class="hidden bg-white rounded-2xl shadow-sm border border-slate-200 p-8" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center"><i data-lucide="file-text" class="w-5 h-5 mr-2 text-indigo-600"></i> Tabel Pengumpulan Data Telaah Dokumen</h3>
            
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border border-slate-200">
                        <tr>
                            <th class="px-4 py-3 border-r border-slate-200 w-12 text-center">No</th>
                            <th class="px-4 py-3 border-r border-slate-200 w-1/3">Aspek yang Ditelaah</th>
                            <th class="px-4 py-3 border-r border-slate-200 w-1/4">Nama Dokumen</th>
                            <th class="px-4 py-3">Hasil Telaah Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="border border-slate-200">
                        @foreach($indicator->documentReviewAspects as $aspect)
                        @php 
                            $docData = $result->documentReviewData->where('assessment_aspect_id', $aspect->id)->first();
                            $val = $docData?->hasil ?? ''; 
                        @endphp
                        <tr class="border-b border-slate-200">
                            <td class="px-4 py-3 border-r border-slate-200 text-center text-slate-500">{{ $aspect->nomor }}</td>
                            <td class="px-4 py-3 border-r border-slate-200 text-slate-700">{{ $aspect->aspek }}</td>
                            <td class="px-4 py-3 border-r border-slate-200 text-slate-600 text-xs font-mono bg-slate-50">
                                <div class="mb-2">{{ $aspect->nama_dokumen }}</div>
                                @if($docData && $docData->file_path)
                                    <a href="{{ asset($docData->file_path) }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-sans font-medium bg-indigo-50 px-2 py-1 rounded border border-indigo-100">
                                        <i data-lucide="download" class="w-3 h-3 mr-1"></i> Buka File
                                    </a>
                                @else
                                    <span class="inline-flex items-center text-slate-400 font-sans font-medium">
                                        <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Belum diunggah
                                    </span>
                                @endif
                            </td>
                            <td class="px-0 py-0 relative">
                                <textarea name="document_review[{{ $aspect->id }}]" rows="2" {{ isset($isReadOnly) && $isReadOnly ? 'readonly disabled' : '' }} class="w-full h-full min-h-[60px] border-0 focus:ring-2 focus:ring-inset focus:ring-indigo-500 resize-y p-3 bg-transparent" placeholder="Catat hasil telaah...">{{ $val }}</textarea>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan Telaah Dokumen</label>
                <textarea name="document_review_note" rows="3" {{ isset($isReadOnly) && $isReadOnly ? 'readonly disabled' : '' }} class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">{{ $result->documentReviewNote->catatan ?? '' }}</textarea>
            </div>
        </div>
        @endif

        @if($indicator->has_wawancara)
        <!-- Wawancara Tab -->
        <div class="hidden bg-white rounded-2xl shadow-sm border border-slate-200 p-8" id="wawancara" role="tabpanel" aria-labelledby="wawancara-tab">
            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center"><i data-lucide="mic" class="w-5 h-5 mr-2 text-indigo-600"></i> Tabel Pengumpulan Data Wawancara</h3>
            
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border border-slate-200">
                        <tr>
                            <th class="px-2 py-3 border-r border-slate-200 w-10 text-center" rowspan="2">No</th>
                            <th class="px-4 py-3 border-r border-slate-200 w-64" rowspan="2">Aspek Wawancara</th>
                            <th class="px-4 py-2 border-b border-slate-200 text-center" colspan="4">Hasil Wawancara Responden</th>
                        </tr>
                        <tr>
                            <th class="px-2 py-2 border-r border-slate-200 text-center w-48">Kepala Sekolah / Wakil</th>
                            <th class="px-2 py-2 border-r border-slate-200 text-center w-48">Kajur / Kapro</th>
                            <th class="px-2 py-2 border-r border-slate-200 text-center w-48">Guru</th>
                            <th class="px-2 py-2 text-center w-48">Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="border border-slate-200 text-xs">
                        @foreach($indicator->interviewAspects as $aspect)
                        <tr class="border-b border-slate-200">
                            <td class="px-2 py-2 border-r border-slate-200 text-center text-slate-500">{{ $aspect->nomor }}</td>
                            <td class="px-4 py-2 border-r border-slate-200 text-slate-700 leading-tight">{{ $aspect->aspek }}</td>
                            
                            @foreach(['kepala_wakil', 'kepala_kompetensi', 'guru', 'siswa'] as $responden)
                            @php $val = $result->interviewData->where('assessment_aspect_id', $aspect->id)->where('responden', $responden)->first()?->hasil ?? ''; @endphp
                            <td class="px-0 py-0 border-r border-slate-200 relative">
                                <textarea name="interview[{{ $aspect->id }}][{{ $responden }}]" rows="3" {{ isset($isReadOnly) && $isReadOnly ? 'readonly disabled' : '' }} class="w-full h-full border-0 focus:ring-2 focus:ring-inset focus:ring-indigo-500 resize-y p-2 bg-transparent text-xs" placeholder="...">{{ $val }}</textarea>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan Wawancara</label>
                <textarea name="interview_note" rows="3" {{ isset($isReadOnly) && $isReadOnly ? 'readonly disabled' : '' }} class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">{{ $result->interviewNote->catatan ?? '' }}</textarea>
            </div>
        </div>
        @endif

        <!-- Kesimpulan & Level Capaian Tab -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8" id="kesimpulan" role="tabpanel" aria-labelledby="kesimpulan-tab">
            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center"><i data-lucide="check-square" class="w-5 h-5 mr-2 text-indigo-600"></i> Penetapan Level Capaian</h3>
            
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 mb-8">
                <p class="text-sm font-medium text-indigo-900 mb-4">Berdasarkan hasil analisis observasi, telaah dokumen, dan wawancara, tetapkan capaian kinerja butir ini pada level (Pilih salah satu):</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($indicator->achievementLevels->reverse() as $level)
                    <label class="relative flex cursor-pointer rounded-xl border {{ old('level_capaian', $result->level_capaian) == $level->level ? 'border-indigo-600 bg-indigo-50/50 ring-1 ring-indigo-600' : 'border-slate-200 bg-white hover:bg-slate-50' }} p-4 shadow-sm focus-within:ring-2 focus-within:ring-indigo-600 transition-all">
                        <input type="radio" name="level_capaian" value="{{ $level->level }}" class="sr-only" {{ old('level_capaian', $result->level_capaian) == $level->level ? 'checked' : '' }} {{ isset($isReadOnly) && $isReadOnly ? 'disabled' : '' }} onchange="updateRadioStyling(this)">
                        <div class="flex flex-col">
                            <span class="block text-base font-bold text-slate-900 mb-1">Level {{ $level->level }}</span>
                            <span class="block text-sm text-slate-600 leading-relaxed">{{ $level->deskripsi }}</span>
                        </div>
                        <!-- Active Indicator Icon -->
                        <i data-lucide="check-circle-2" class="h-6 w-6 text-indigo-600 absolute top-4 right-4 transition-opacity {{ old('level_capaian', $result->level_capaian) == $level->level ? 'opacity-100' : 'opacity-0' }}"></i>
                    </label>
                    @endforeach
                </div>
                @error('level_capaian')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-end mb-2">
                    <label for="kesimpulan" class="block text-sm font-bold text-slate-900">Uraian Kesimpulan Penilaian <span class="text-red-500">*</span></label>
                    <span id="word-counter" class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded">0 / 50 Minimal Kata</span>
                </div>
                <p class="text-xs text-slate-500 mb-3">Tuliskan kesimpulan menyeluruh berdasarkan bukti faktual dari tabel kerja. Pemilihan level harus sejalan dengan uraian di bawah ini.</p>
                <textarea id="kesimpulan" name="kesimpulan" rows="6" {{ isset($isReadOnly) && $isReadOnly ? 'readonly disabled' : 'required' }}
                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-4 border @error('kesimpulan') border-red-300 ring-red-500 @enderror" 
                    placeholder="Berdasarkan analisis pada tabel kerja yang dilakukan melalui observasi, telaah dokumen, dan wawancara, dapat disimpulkan bahwa...">{{ old('kesimpulan', $result->kesimpulan) }}</textarea>
                @error('kesimpulan')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between">
                <a href="{{ route('evaluations.show', $evaluation) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Kembali</a>
                @if(!isset($isReadOnly) || !$isReadOnly)
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700 hover:-translate-y-0.5 transition-all flex items-center">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Penilaian Indikator
                </button>
                @endif
            </div>
        </div>

    </div>
</form>

<script>
    // Simple Tab implementation
    document.querySelectorAll('[data-tabs-toggle] button').forEach(button => {
        button.addEventListener('click', () => {
            // Remove active from all tabs
            document.querySelectorAll('[data-tabs-toggle] button').forEach(b => {
                b.classList.remove('text-indigo-600', 'border-indigo-600', 'font-bold');
                b.classList.add('hover:text-slate-600', 'hover:border-slate-300');
            });
            // Add active to clicked tab
            button.classList.remove('hover:text-slate-600', 'hover:border-slate-300');
            button.classList.add('text-indigo-600', 'border-indigo-600', 'font-bold');
            
            // Hide all panels
            document.querySelectorAll('#myTabContent > div').forEach(p => p.classList.add('hidden'));
            // Show target panel
            document.querySelector(button.dataset.tabsTarget).classList.remove('hidden');
        });
    });

    // Word counter for kesimpulan
    const textarea = document.getElementById('kesimpulan');
    const counter = document.getElementById('word-counter');
    
    function updateCounter() {
        const text = textarea.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        counter.textContent = `${words} / 50 Minimal Kata`;
        if (words >= 50) {
            counter.classList.replace('text-slate-500', 'text-emerald-600');
            counter.classList.replace('bg-slate-100', 'bg-emerald-50');
        } else {
            counter.classList.replace('text-emerald-600', 'text-slate-500');
            counter.classList.replace('bg-emerald-50', 'bg-slate-100');
        }
    }
    
    textarea.addEventListener('input', updateCounter);
    // Init counter
    updateCounter();

    // Custom Radio Button Styling
    function updateRadioStyling(selectedRadio) {
        // Reset all labels
        document.querySelectorAll('input[name="level_capaian"]').forEach(radio => {
            const label = radio.closest('label');
            const icon = label.querySelector('i[data-lucide]');
            
            label.classList.remove('border-indigo-600', 'bg-indigo-50/50', 'ring-1', 'ring-indigo-600');
            label.classList.add('border-slate-200', 'bg-white', 'hover:bg-slate-50');
            icon.classList.remove('opacity-100');
            icon.classList.add('opacity-0');
        });

        // Set selected label
        const selectedLabel = selectedRadio.closest('label');
        const selectedIcon = selectedLabel.querySelector('i[data-lucide]');
        
        selectedLabel.classList.remove('border-slate-200', 'bg-white', 'hover:bg-slate-50');
        selectedLabel.classList.add('border-indigo-600', 'bg-indigo-50/50', 'ring-1', 'ring-indigo-600');
        selectedIcon.classList.remove('opacity-0');
        selectedIcon.classList.add('opacity-100');
    }
</script>
@endsection
