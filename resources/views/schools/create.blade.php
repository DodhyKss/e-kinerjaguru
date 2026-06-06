@extends('layouts.app')
@section('title', 'Tambah Data Sekolah')

@section('content')
<div class="mb-6">
    <a href="{{ route('schools.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Sekolah
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-3xl">
    <div class="px-8 py-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-900">Form Tambah Sekolah</h3>
        <p class="text-sm text-slate-500 mt-1">Masukkan data detail sekolah dengan lengkap dan benar.</p>
    </div>
    
    <form action="{{ route('schools.store') }}" method="POST" class="p-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-1">Nama Sekolah <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('nama') border-rose-300 ring-rose-500 @enderror">
                @error('nama') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="npsn" class="block text-sm font-bold text-slate-700 mb-1">NPSN <span class="text-rose-500">*</span></label>
                <input type="text" name="npsn" id="npsn" value="{{ old('npsn') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('npsn') border-rose-300 ring-rose-500 @enderror">
                @error('npsn') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="kepala_sekolah" class="block text-sm font-bold text-slate-700 mb-1">Nama Kepala Sekolah <span class="text-rose-500">*</span></label>
                <input type="text" name="kepala_sekolah" id="kepala_sekolah" value="{{ old('kepala_sekolah') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('kepala_sekolah') border-rose-300 ring-rose-500 @enderror">
                @error('kepala_sekolah') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="alamat" class="block text-sm font-bold text-slate-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('alamat') border-rose-300 ring-rose-500 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kabupaten" class="block text-sm font-bold text-slate-700 mb-1">Kabupaten/Kota <span class="text-rose-500">*</span></label>
                <input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('kabupaten') border-rose-300 ring-rose-500 @enderror">
                @error('kabupaten') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="provinsi" class="block text-sm font-bold text-slate-700 mb-1">Provinsi <span class="text-rose-500">*</span></label>
                <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', 'Jawa Barat') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('provinsi') border-rose-300 ring-rose-500 @enderror">
                @error('provinsi') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="telepon" class="block text-sm font-bold text-slate-700 mb-1">Nomor Telepon</label>
                <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>

            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email Sekolah</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>

            <div>
                <label for="status" class="block text-sm font-bold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                <select name="status" id="status" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Reset</button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
