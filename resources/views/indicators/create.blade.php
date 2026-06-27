@extends('layouts.app')
@section('title', 'Tambah Indikator Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('indicators.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Indikator
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-4xl">
    <div class="px-8 py-6 border-b border-slate-100 flex items-start gap-4 bg-indigo-50/30">
        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
            <i data-lucide="book-plus" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Form Tambah Indikator Dasar</h3>
            <p class="text-sm text-slate-600 mt-1">Langkah 1: Tentukan Dimensi, Kode, Nama, dan Metode Pengumpulan Data untuk indikator ini. Anda akan menambahkan rincian Level dan Aspek di langkah selanjutnya.</p>
        </div>
    </div>
    
    <form action="{{ route('indicators.store') }}" method="POST" class="p-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="md:col-span-3">
                <label for="dimension_id" class="block text-sm font-bold text-slate-700 mb-1">Komponen / Dimensi Utama <span class="text-rose-500">*</span></label>
                <select name="dimension_id" id="dimension_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('dimension_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Dimensi --</option>
                    @foreach($dimensions as $dim)
                        <option value="{{ $dim->id }}" {{ old('dimension_id') == $dim->id ? 'selected' : '' }}>{{ $dim->urutan_romawi ?? $dim->urutan }}. {{ $dim->nama }}</option>
                    @endforeach
                </select>
                @error('dimension_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="kode" class="block text-sm font-bold text-slate-700 mb-1">Kode Indikator <span class="text-rose-500">*</span></label>
                <input type="text" name="kode" id="kode" value="{{ old('kode') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('kode') border-rose-300 ring-rose-500 @enderror" placeholder="Contoh: MG01">
                @error('kode') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="urutan" class="block text-sm font-bold text-slate-700 mb-1">Urutan Dimensi <span class="text-rose-500">*</span></label>
                <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $nextUrutan ?? 1) }}" required min="1" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('urutan') border-rose-300 ring-rose-500 @enderror">
                @error('urutan') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="urutan_keseluruhan" class="block text-sm font-bold text-slate-700 mb-1">Urutan Keseluruhan <span class="text-rose-500">*</span></label>
                <input type="number" name="urutan_keseluruhan" id="urutan_keseluruhan" value="{{ old('urutan_keseluruhan', $nextUrutanKeseluruhan ?? 1) }}" required min="1" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('urutan_keseluruhan') border-rose-300 ring-rose-500 @enderror">
                @error('urutan_keseluruhan') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-1">Dimensi Kinerja / Butir Penilaian <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('nama') border-rose-300 ring-rose-500 @enderror" placeholder="Contoh: Perencanaan Pembelajaran">
                @error('nama') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
                <label for="deskripsi" class="block text-sm font-bold text-slate-700 mb-1">Deskripsi & Ruang Lingkup <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" id="deskripsi" rows="3" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('deskripsi') border-rose-300 ring-rose-500 @enderror" placeholder="Jelaskan ruang lingkup penilaian butir ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-sm font-bold text-slate-700 mb-3">Metode Pengumpulan Data yang Tersedia <span class="text-rose-500">*</span></label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="has_observasi" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-600" {{ old('has_observasi') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-slate-700 font-medium">Observasi Lapangan</span>
                    </label>
                    <label class="inline-flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="has_telaah_dokumen" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-600" {{ old('has_telaah_dokumen') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-slate-700 font-medium">Telaah Dokumen</span>
                    </label>
                    <label class="inline-flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="has_wawancara" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-600" {{ old('has_wawancara') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-slate-700 font-medium">Wawancara</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Reset</button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                Lanjut Kelola Level & Aspek <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urutanData = @json($nextUrutanByDimension);
        const dimensionSelect = document.getElementById('dimension_id');
        const urutanInput = document.getElementById('urutan');
        
        dimensionSelect.addEventListener('change', function() {
            const dimId = this.value;
            if (dimId && urutanData[dimId]) {
                urutanInput.value = urutanData[dimId];
            } else {
                urutanInput.value = '';
            }
        });
        
        // Trigger initially if there's already a value selected
        if (dimensionSelect.value && !urutanInput.value) {
            dimensionSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
