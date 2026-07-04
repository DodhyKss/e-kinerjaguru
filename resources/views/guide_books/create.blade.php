@extends('layouts.app')
@section('title', 'Unggah Buku Panduan PDF')

@section('content')
<div class="mb-6">
    <a href="{{ route('guide-books.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Panduan
    </a>
</div>

<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">Form Unggah Buku Panduan PDF</h2>
        <p class="text-xs text-slate-500 mt-1">Unggah dokumen petunjuk teknis / panduan penggunaan E-Kinerja Guru SMK dalam format PDF.</p>
    </div>
    
    <div class="p-8">
        <form action="{{ route('guide-books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label for="judul" class="block text-sm font-semibold text-slate-700 mb-2">Judul Buku Panduan <span class="text-red-500">*</span></label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', 'Buku Panduan E-Kinerja Guru SMK Tahun ' . date('Y')) }}" required
                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('judul') border-red-300 ring-red-500 @enderror"
                    placeholder="Contoh: Buku Panduan E-Kinerja Guru SMK 2026">
                @error('judul')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="file_pdf" class="block text-sm font-semibold text-slate-700 mb-2">File PDF Buku Panduan <span class="text-red-500">*</span></label>
                <div class="relative border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-6 transition-colors bg-slate-50/50 hover:bg-indigo-50/10 text-center">
                    <input type="file" name="file_pdf" id="file_pdf" accept=".pdf" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="document.getElementById('file-name-display').innerText = this.files[0] ? this.files[0].name : 'Belum ada file dipilih';">
                    
                    <div class="flex flex-col items-center justify-center pointer-events-none">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mb-3">
                            <i data-lucide="file-up" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Klik untuk memilih file PDF atau seret ke sini</p>
                        <p class="text-xs text-slate-400 mt-1">Format file wajib .PDF (Maksimal ukuran 10 MB)</p>
                        <div class="mt-3 inline-flex items-center px-3 py-1 bg-white border border-slate-200 rounded-lg shadow-sm text-xs font-mono text-indigo-600 font-medium">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 mr-1.5"></i>
                            <span id="file-name-display">Belum ada file dipilih</span>
                        </div>
                    </div>
                </div>
                @error('file_pdf')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8 bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 flex items-start gap-3">
                <div class="flex items-center h-5 mt-0.5">
                    <input id="is_active" name="is_active" type="checkbox" value="1" checked
                        class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                </div>
                <div class="text-sm">
                    <label for="is_active" class="font-semibold text-slate-800 cursor-pointer">Jadikan sebagai Buku Panduan Aktif</label>
                    <p class="text-xs text-slate-500 mt-0.5">Jika dicentang, file ini akan langsung aktif dan menjadi file yang diunduh oleh pengguna di halaman login maupun panduan online.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('guide-books.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center shadow-sm shadow-indigo-200">
                    <i data-lucide="upload" class="w-4 h-4 mr-2"></i> Unggah & Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
