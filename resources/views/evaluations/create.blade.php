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
                <label for="guru_id" class="block text-sm font-bold text-slate-700 mb-1">Guru Yang Akan Dinilai <span class="text-rose-500">*</span></label>
                <select name="guru_id" id="guru_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('guru_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama }} - {{ $guru->mata_pelajaran }} @if(auth()->user()->isAdmin()) ({{ $guru->school->nama }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('guru_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="penilai_id" class="block text-sm font-bold text-slate-700 mb-1">Asesor / Penilai <span class="text-rose-500">*</span></label>
                <select name="penilai_id" id="penilai_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('penilai_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Asesor Yang Ditugaskan --</option>
                    @foreach($penilais as $penilai)
                        <option value="{{ $penilai->id }}" {{ old('penilai_id') == $penilai->id ? 'selected' : '' }}>
                            {{ $penilai->nama }} - {{ $penilai->jabatan }} @if(auth()->user()->isAdmin()) ({{ $penilai->school->nama }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('penilai_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                <p class="text-xs text-slate-500 mt-2"><i data-lucide="info" class="w-3 h-3 inline"></i> Asesor yang dipilih akan menerima tugas ini di dashboard mereka.</p>
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
@endsection
