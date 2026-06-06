@extends('layouts.app')
@section('title', 'Edit Periode Evaluasi')

@section('content')
<div class="mb-6">
    <a href="{{ route('evaluation-periods.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Periode
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-3xl">
    <div class="px-8 py-6 border-b border-slate-100 flex items-start gap-4 bg-indigo-50/30">
        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
            <i data-lucide="calendar-edit" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Form Edit Periode</h3>
            <p class="text-sm text-slate-600 mt-1">Perbarui jadwal atau status pelaksanaan evaluasi kinerja.</p>
        </div>
    </div>
    
    <form action="{{ route('evaluation-periods.update', $evaluationPeriod) }}" method="POST" class="p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label for="school_id" class="block text-sm font-bold text-slate-700 mb-1">Pilih Sekolah <span class="text-rose-500">*</span></label>
                <select name="school_id" id="school_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('school_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Sekolah Sasaran Evaluasi --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', $evaluationPeriod->school_id) == $school->id ? 'selected' : '' }}>{{ $school->npsn }} - {{ $school->nama }}</option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-1">Nama Periode Evaluasi <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $evaluationPeriod->nama) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('nama') border-rose-300 ring-rose-500 @enderror">
                @error('nama') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="tahun_ajaran" class="block text-sm font-bold text-slate-700 mb-1">Tahun Ajaran <span class="text-rose-500">*</span></label>
                <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran', $evaluationPeriod->tahun_ajaran) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('tahun_ajaran') border-rose-300 ring-rose-500 @enderror">
                @error('tahun_ajaran') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="semester" class="block text-sm font-bold text-slate-700 mb-1">Semester <span class="text-rose-500">*</span></label>
                <select name="semester" id="semester" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('semester') border-rose-300 ring-rose-500 @enderror">
                    <option value="ganjil" {{ old('semester', $evaluationPeriod->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="genap" {{ old('semester', $evaluationPeriod->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tanggal_mulai" class="block text-sm font-bold text-slate-700 mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($evaluationPeriod->tanggal_mulai)->format('Y-m-d')) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('tanggal_mulai') border-rose-300 ring-rose-500 @enderror">
                @error('tanggal_mulai') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tanggal_selesai" class="block text-sm font-bold text-slate-700 mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($evaluationPeriod->tanggal_selesai)->format('Y-m-d')) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('tanggal_selesai') border-rose-300 ring-rose-500 @enderror">
                @error('tanggal_selesai') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-bold text-slate-700 mb-1">Status Periode <span class="text-rose-500">*</span></label>
                <select name="status" id="status" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('status') border-rose-300 ring-rose-500 @enderror">
                    <option value="aktif" {{ old('status', $evaluationPeriod->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ old('status', $evaluationPeriod->status) == 'selesai' ? 'selected' : '' }}>Selesai / Ditutup</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('evaluation-periods.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Perbarui Periode
            </button>
        </div>
    </form>
</div>
@endsection
