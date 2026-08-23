@extends('layouts.app')
@section('title', 'Buat Penugasan Evaluasi Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('evaluations.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Evaluasi
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-2xl">
    <div class="px-8 py-6 border-b border-slate-100 flex items-start gap-4 bg-indigo-50/30">
        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
            <i data-lucide="clipboard-signature" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Penugasan Evaluasi Baru</h3>
            <p class="text-sm text-slate-600 mt-1">Tugaskan Asesor untuk melakukan penilaian kinerja pada seorang Guru di periode tertentu.</p>
        </div>
    </div>
    
    <form action="{{ route('evaluations.store') }}" method="POST" class="p-8">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="evaluation_period_id" class="block text-sm font-bold text-slate-700 mb-1">Periode Evaluasi <span class="text-rose-500">*</span></label>
                <select name="evaluation_period_id" id="evaluation_period_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('evaluation_period_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Periode Aktif --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ old('evaluation_period_id') == $period->id ? 'selected' : '' }}>
                            {{ $period->nama }} ({{ $period->school->nama }})
                        </option>
                    @endforeach
                </select>
                @error('evaluation_period_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="penilai_id" class="block text-sm font-bold text-slate-700 mb-1">Asesor / Evaluator <span class="text-rose-500">*</span></label>
                <select name="penilai_id" id="penilai_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('penilai_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Asesor atau Kepala Sekolah --</option>
                    @if($penilais->where('jabatan', 'Kepala Sekolah')->isNotEmpty())
                        <optgroup label="Kepala Sekolah (Evaluator)">
                            @foreach($penilais->where('jabatan', 'Kepala Sekolah') as $penilai)
                                <option value="{{ $penilai->id }}" data-is-kepsek="true" data-school-id="{{ $penilai->school_id }}" {{ old('penilai_id') == $penilai->id ? 'selected' : '' }}>
                                    [Kepala Sekolah] {{ $penilai->nama }} @if(auth()->user()->isAdmin() && $penilai->school) ({{ $penilai->school->nama }}) @endif
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                    @if($penilais->where('jabatan', '!=', 'Kepala Sekolah')->isNotEmpty())
                        <optgroup label="Asesor / Penilai Guru">
                            @foreach($penilais->where('jabatan', '!=', 'Kepala Sekolah') as $penilai)
                                <option value="{{ $penilai->id }}" data-is-kepsek="false" data-school-id="{{ $penilai->school_id }}" {{ old('penilai_id') == $penilai->id ? 'selected' : '' }}>
                                    {{ $penilai->nama }} - {{ $penilai->jabatan }} @if(auth()->user()->isAdmin() && $penilai->school) ({{ $penilai->school->nama }}) @endif
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                @error('penilai_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                <p id="penilai-helper-text" class="text-xs text-slate-500 mt-2"><i data-lucide="info" class="w-3 h-3 inline"></i> Evaluator dari sekolah lain otomatis disembunyikan setelah Periode Evaluasi dipilih.</p>

                <!-- Fitur ALL: Tugaskan sekaligus ke semua guru milik asesor -->
                <div id="assign-all-wrapper" class="hidden mt-3 bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                    <label class="flex items-start cursor-pointer" for="assign_all">
                        <input type="checkbox" name="assign_all" id="assign_all" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <span class="ml-3 select-none">
                            <span class="block text-sm font-bold text-slate-900">Tugaskan Langsung ke SEMUA Guru (ALL)</span>
                            <span id="assign-all-info" class="block text-xs text-slate-600 mt-0.5">Buatkan penugasan evaluasi sekaligus untuk semua guru yang ditugaskan pada asesor ini.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div id="guru-field-wrapper">
                <label for="guru_id" class="block text-sm font-bold text-slate-700 mb-1">Guru Yang Akan Dinilai <span class="text-rose-500">*</span></label>
                <select name="guru_id" id="guru_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('guru_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama }} - {{ $guru->mata_pelajaran }} @if(auth()->user()->isAdmin()) ({{ $guru->school->nama }}) @endif
                        </option>
                    @endforeach
                </select>
                <p id="guru-helper-text" class="text-xs text-slate-500 mt-2"></p>
                @error('guru_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Reset</button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                <i data-lucide="send" class="w-4 h-4 mr-2"></i> Buat Penugasan
            </button>
        </div>
    </form>
</div>

@php
    $penilaiGurusMap = $penilais->mapWithKeys(function ($p) use ($gurus) {
        if ($p->jabatan === 'Kepala Sekolah') {
            return [$p->id => $gurus->where('school_id', $p->school_id)->pluck('id')->values()->toArray()];
        } else {
            return [$p->id => $p->gurus->pluck('id')->values()->toArray()];
        }
    });
@endphp

<script>
$(document).ready(function() {
    $('#evaluation_period_id').select2({ width: '100%' });
    $('#penilai_id').select2({ width: '100%' });
    $('#guru_id').select2({ width: '100%' });

    const periodSelect = $('#evaluation_period_id');
    const penilaiSelect = $('#penilai_id');
    const guruSelect = $('#guru_id');
    const helperText = $('#guru-helper-text');
    const assignAllWrapper = $('#assign-all-wrapper');
    const assignAllCheckbox = $('#assign_all');
    const assignAllInfo = $('#assign-all-info');
    const guruFieldWrapper = $('#guru-field-wrapper');
    
    const penilaiGurusMap = @json($penilaiGurusMap);
    // Peta: id periode evaluasi -> school_id (untuk memfilter evaluator dari sekolah lain)
    const periodSchoolMap = @json($periods->pluck('school_id', 'id'));
    
    // Simpan semua options evaluator beserta grupnya
    const penilaiPlaceholderText = penilaiSelect.find('option:first').text();
    const allPenilaiOptions = [];
    penilaiSelect.find('optgroup').each(function() {
        const groupLabel = $(this).attr('label');
        $(this).find('option').each(function() {
            if ($(this).val() !== "") {
                allPenilaiOptions.push({ group: groupLabel, option: $(this).clone() });
            }
        });
    });
    
    // Simpan semua options guru ke array
    const allGuruOptions = [];
    guruSelect.find('option').each(function() {
        if ($(this).val() !== "") {
            allGuruOptions.push($(this).clone());
        }
    });
    
    const oldGuruId = "{{ old('guru_id') }}";

    // Filter opsi Evaluator: hanya tampilkan evaluator dari sekolah pada Periode Evaluasi yang dipilih.
    // Opsi yang tidak sesuai dihapus dari DOM (bukan sekadar disabled) agar benar-benar tidak muncul.
    function filterPenilaiOptions() {
        const selectedPeriodId = periodSelect.val();
        const periodSchoolId = selectedPeriodId ? String(periodSchoolMap[selectedPeriodId] ?? '') : '';
        const currentVal = String(penilaiSelect.val() ?? '');

        penilaiSelect.empty().append(`<option value="">${penilaiPlaceholderText}</option>`);

        let currentGroupEl = null;
        let restored = false;
        let shown = 0;

        allPenilaiOptions.forEach(item => {
            const optionSchoolId = String(item.option.attr('data-school-id') ?? '');

            // Sembunyikan evaluator dari sekolah lain ketika periode sudah dipilih
            if (periodSchoolId !== '' && optionSchoolId !== '' && optionSchoolId !== periodSchoolId) {
                return;
            }

            if (!currentGroupEl || currentGroupEl.attr('label') !== item.group) {
                currentGroupEl = $('<optgroup>').attr('label', item.group);
                penilaiSelect.append(currentGroupEl);
            }

            const clone = item.option.clone();
            if (clone.val() === currentVal) {
                clone.prop('selected', true);
                restored = true;
            }
            currentGroupEl.append(clone);
            shown++;
        });

        let selectionCleared = false;
        if (!restored) {
            selectionCleared = currentVal !== '';
            penilaiSelect.val('');
        }

        penilaiSelect.trigger('change.select2');
        return selectionCleared;
    }

    function updateAssignAllVisibility() {
        const selectedPenilaiId = penilaiSelect.val();
        const allowedGuruIds = (selectedPenilaiId && penilaiGurusMap[selectedPenilaiId]) || [];
        
        if (selectedPenilaiId && allowedGuruIds.length > 0) {
            assignAllWrapper.removeClass('hidden');
            assignAllInfo.text(`Buatkan ${allowedGuruIds.length} penugasan evaluasi sekaligus untuk semua guru yang ditugaskan pada asesor ini.`);
        } else {
            assignAllWrapper.addClass('hidden');
            assignAllCheckbox.prop('checked', false).trigger('change');
        }
    }

    function toggleGuruField() {
        if (assignAllCheckbox.is(':checked')) {
            guruFieldWrapper.addClass('hidden opacity-50 pointer-events-none');
            guruSelect.prop('disabled', true).val('').trigger('change.select2');
        } else {
            guruFieldWrapper.removeClass('hidden opacity-50 pointer-events-none');
            guruSelect.prop('disabled', false);
            filterGurus();
        }
    }

    function filterGurus() {
        const selectedPenilaiId = penilaiSelect.val();
        const currentGuruVal = guruSelect.val();
        const selectedOption = penilaiSelect.find('option:selected');
        const isKepsek = selectedOption.length > 0 && selectedOption.attr('data-is-kepsek') === 'true';
        
        guruSelect.empty().append('<option value="">-- Pilih Guru --</option>');
        
        if (!selectedPenilaiId) {
            allGuruOptions.forEach(opt => guruSelect.append(opt.clone()));
            if (helperText.length) {
                helperText.text("Silakan pilih Asesor/Evaluator terlebih dahulu untuk memfilter daftar guru.");
                helperText.attr('class', 'text-xs text-amber-600 mt-2');
            }
            guruSelect.trigger('change.select2'); // Trigger select2 refresh without firing standard change
            return;
        }
        
        const allowedGuruIds = penilaiGurusMap[selectedPenilaiId] || [];
        
        if (allowedGuruIds.length === 0) {
            if (helperText.length) {
                if (isKepsek) {
                    helperText.text("⚠️ Belum ada data guru yang terdaftar di sekolah Kepala Sekolah ini.");
                } else {
                    helperText.text("⚠️ Asesor ini belum ditugaskan ke guru manapun di menu Asesor. Silakan edit data Asesor terlebih dahulu.");
                }
                helperText.attr('class', 'text-xs text-rose-600 font-medium mt-2');
            }
        } else {
            let count = 0;
            allGuruOptions.forEach(opt => {
                if (allowedGuruIds.includes(parseInt(opt.val()))) {
                    const clone = opt.clone();
                    if (clone.val() === currentGuruVal || clone.val() === oldGuruId) {
                        clone.prop('selected', true);
                    }
                    guruSelect.append(clone);
                    count++;
                }
            });
            if (helperText.length) {
                if (isKepsek) {
                    helperText.text(`Menampilkan ${count} guru yang ada di sekolah Kepala Sekolah ini.`);
                } else {
                    helperText.text(`Menampilkan ${count} guru yang ditugaskan kepada asesor ini.`);
                }
                helperText.attr('class', 'text-xs text-emerald-600 font-medium mt-2');
            }
        }
        guruSelect.trigger('change.select2');
    }

    periodSelect.on('change', function() {
        const wasCleared = filterPenilaiOptions();
        if (wasCleared) {
            filterGurus();
        }
        updateAssignAllVisibility();
    });
    penilaiSelect.on('change', function() {
        filterGurus();
        updateAssignAllVisibility();
    });
    assignAllCheckbox.on('change', toggleGuruField);

    filterPenilaiOptions();
    filterGurus();
    updateAssignAllVisibility();
    toggleGuruField();
});
</script>
@endsection
