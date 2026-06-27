@extends('layouts.app')
@section('title', 'Edit Indikator')

@section('content')
<div class="mb-6">
    <a href="{{ route('indicators.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Indikator
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-4xl">
    <div class="px-8 py-6 border-b border-slate-100 flex items-start gap-4 bg-indigo-50/30">
        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
            <i data-lucide="edit-3" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Edit Identitas Indikator</h3>
            <p class="text-sm text-slate-600 mt-1">Perbarui data utama indikator. Untuk mengubah level dan aspek penilaian, silakan masuk ke halaman "Kelola Aspek & Level".</p>
        </div>
    </div>
    
    <form action="{{ route('indicators.update', $indicator) }}" method="POST" class="p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="md:col-span-3">
                <label for="dimension_id" class="block text-sm font-bold text-slate-700 mb-1">Komponen / Dimensi Utama <span class="text-rose-500">*</span></label>
                <select name="dimension_id" id="dimension_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('dimension_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Dimensi --</option>
                    @foreach($dimensions as $dim)
                        <option value="{{ $dim->id }}" {{ old('dimension_id', $indicator->dimension_id) == $dim->id ? 'selected' : '' }}>{{ $dim->urutan_romawi ?? $dim->urutan }}. {{ $dim->nama }}</option>
                    @endforeach
                </select>
                @error('dimension_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="kode" class="block text-sm font-bold text-slate-700 mb-1">Kode Indikator <span class="text-rose-500">*</span></label>
                <input type="text" name="kode" id="kode" value="{{ old('kode', $indicator->kode) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('kode') border-rose-300 ring-rose-500 @enderror">
                @error('kode') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="urutan" class="block text-sm font-bold text-slate-700 mb-1">Urutan Dimensi <span class="text-rose-500">*</span></label>
                <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $indicator->urutan) }}" required min="1" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('urutan') border-rose-300 ring-rose-500 @enderror">
                @error('urutan') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="urutan_keseluruhan" class="block text-sm font-bold text-slate-700 mb-1">Urutan Keseluruhan <span class="text-rose-500">*</span></label>
                <input type="number" name="urutan_keseluruhan" id="urutan_keseluruhan" value="{{ old('urutan_keseluruhan', $indicator->urutan_keseluruhan) }}" required min="1" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('urutan_keseluruhan') border-rose-300 ring-rose-500 @enderror">
                @error('urutan_keseluruhan') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-1">Dimensi Kinerja / Butir Penilaian <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $indicator->nama) }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('nama') border-rose-300 ring-rose-500 @enderror">
                @error('nama') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
                <label for="deskripsi" class="block text-sm font-bold text-slate-700 mb-1">Deskripsi & Ruang Lingkup <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" id="deskripsi" rows="3" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('deskripsi') border-rose-300 ring-rose-500 @enderror">{{ old('deskripsi', $indicator->deskripsi) }}</textarea>
                @error('deskripsi') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-sm font-bold text-slate-700 mb-3">Metode Pengumpulan Data yang Tersedia <span class="text-rose-500">*</span></label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="has_observasi" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-600" {{ old('has_observasi', $indicator->has_observasi) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-slate-700 font-medium">Observasi Lapangan</span>
                    </label>
                    <label class="inline-flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="has_telaah_dokumen" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-600" {{ old('has_telaah_dokumen', $indicator->has_telaah_dokumen) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-slate-700 font-medium">Telaah Dokumen</span>
                    </label>
                    <label class="inline-flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="has_wawancara" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-600" {{ old('has_wawancara', $indicator->has_wawancara) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-slate-700 font-medium">Wawancara</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('indicators.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Perbarui Indikator
            </button>
        </div>
    </form>
</div>
@endsection
