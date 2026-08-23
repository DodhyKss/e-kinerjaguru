@extends('layouts.app')
@section('title', 'Edit Penugasan Evaluasi')

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
            <h3 class="text-lg font-bold text-slate-900">Edit Penugasan Evaluasi</h3>
            <p class="text-sm text-slate-600 mt-1">Ubah data Asesor atau Guru pada penugasan evaluasi ini.</p>
        </div>
    </div>
    
    <form action="{{ route('evaluations.update', $evaluation) }}" method="POST" class="p-8">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <div>
                <label for="evaluation_period_id" class="block text-sm font-bold text-slate-700 mb-1">Periode Evaluasi <span class="text-rose-500">*</span></label>
                <select name="evaluation_period_id" id="evaluation_period_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('evaluation_period_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Periode Aktif --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ old('evaluation_period_id', $evaluation->evaluation_period_id) == $period->id ? 'selected' : '' }}>
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
                                <option value="{{ $penilai->id }}" data-is-kepsek="true" data-school-id="{{ $penilai->school_id }}" {{ old('penilai_id', $evaluation->penilai_id) == $penilai->id ? 'selected' : '' }}>
                                    [Kepala Sekolah] {{ $penilai->nama }} @if(auth()->user()->isAdmin() && $penilai->school) ({{ $penilai->school->nama }}) @endif
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                    @if($penilais->where('jabatan', '!=', 'Kepala Sekolah')->isNotEmpty())
                        <optgroup label="Asesor / Penilai Guru">
                            @foreach($penilais->where('jabatan', '!=', 'Kepala Sekolah') as $penilai)
                                <option value="{{ $penilai->id }}" data-is-kepsek="false" data-school-id="{{ $penilai->school_id }}" {{ old('penilai_id', $evaluation->penilai_id) == $penilai->id ? 'selected' : '' }}>
                                    {{ $penilai->nama }} - {{ $penilai->jabatan }} @if(auth()->user()->isAdmin() && $penilai->school) ({{ $penilai->school->nama }}) @endif
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                @error('penilai_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                <p class="text-xs text-slate-500 mt-2"><i data-lucide="info" class="w-3 h-3 inline"></i> Evaluator yang dipilih (Kepala Sekolah / Asesor) akan menentukan daftar guru yang muncul di bawah.</p>
            </div>

            <div>
                <label for="guru_id" class="block text-sm font-bold text-slate-700 mb-1">Guru Yang Akan Dinilai <span class="text-rose-500">*</span></label>
                <select name="guru_id" id="guru_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('guru_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_id', $evaluation->guru_id) == $guru->id ? 'selected' : '' }}>
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
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Perubahan
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
    
    const penilaiGurusMap = @json($penilaiGurusMap);
    // Peta: id periode evaluasi -> school_id (untuk memfilter Kepala Sekolah dari sekolah lain)
    const periodSchoolMap = @json($periods->pluck('school_id', 'id'));
    
    // Simpan semua options guru ke array
    const allGuruOptions = [];
    guruSelect.find('option').each(function() {
        if ($(this).val() !== "") {
            allGuruOptions.push($(this).clone());
        }
    });
    
    const oldGuruId = "{{ old('guru_id', $evaluation->guru_id) }}";

    // Filter opsi Kepala Sekolah: hanya tampilkan kepsek dari sekolah pada Periode Evaluasi yang dipilih
    function filterKepsekOptions() {
        const selectedPeriodId = periodSelect.val();
        const periodSchoolId = selectedPeriodId ? String(periodSchoolMap[selectedPeriodId] ?? '') : '';
        let currentVal = penilaiSelect.val();
        let selectionInvalid = false;

        if (periodSchoolId !== '') {
            penilaiSelect.find('option[data-is-kepsek="true"]').each(function() {
                const optionSchoolId = String($(this).attr('data-school-id') ?? '');
                const blocked = optionSchoolId !== '' && optionSchoolId !== periodSchoolId;
                $(this).prop('disabled', blocked);
                if (blocked && String(currentVal) === String($(this).val())) {
                    selectionInvalid = true;
                }
            });
        } else {
            penilaiSelect.find('option[data-is-kepsek="true"]').each(function() {
                $(this).prop('disabled', false);
            });
        }

        if (selectionInvalid) {
            currentVal = null;
            penilaiSelect.val('').trigger('change.select2');
        }

        penilaiSelect.trigger('change.select2');
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

    periodSelect.on('change', filterKepsekOptions);
    penilaiSelect.on('change', filterGurus);
    filterKepsekOptions();
    filterGurus();
});
</script>
@endsection
