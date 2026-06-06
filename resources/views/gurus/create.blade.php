@extends('layouts.app')
@section('title', 'Tambah Data Guru')

@section('content')
<div class="mb-6">
    <a href="{{ route('gurus.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Guru
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-4xl">
    <div class="px-8 py-6 border-b border-slate-100 flex items-start gap-4 bg-indigo-50/30">
        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
            <i data-lucide="user-plus" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Form Tambah Guru</h3>
            <p class="text-sm text-slate-600 mt-1">Sistem akan secara otomatis membuatkan akun pengguna dengan <span class="font-bold text-indigo-700">password default menggunakan NIP</span>.</p>
        </div>
    </div>
    
    <form action="{{ route('gurus.store') }}" method="POST" class="p-8">
        @csrf
        
        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Informasi Kepegawaian</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="md:col-span-2">
                <label for="school_id" class="block text-sm font-bold text-slate-700 mb-1">Asal Sekolah <span class="text-rose-500">*</span></label>
                <select name="school_id" id="school_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('school_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->npsn }} - {{ $school->nama }}</option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap (beserta gelar) <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('nama') border-rose-300 ring-rose-500 @enderror">
                @error('nama') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email (Untuk Login) <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('email') border-rose-300 ring-rose-500 @enderror">
                @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nip" class="block text-sm font-bold text-slate-700 mb-1">NIP (Juga untuk Password) <span class="text-rose-500">*</span></label>
                <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('nip') border-rose-300 ring-rose-500 @enderror">
                @error('nip') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nuptk" class="block text-sm font-bold text-slate-700 mb-1">NUPTK</label>
                <input type="text" name="nuptk" id="nuptk" value="{{ old('nuptk') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>

            <div>
                <label for="jenis_kelamin" class="block text-sm font-bold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                <select name="jenis_kelamin" id="jenis_kelamin" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div>
                <label for="no_telepon" class="block text-sm font-bold text-slate-700 mb-1">Nomor Telepon/HP</label>
                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>
        </div>

        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Informasi Tugas & Jabatan</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="mata_pelajaran" class="block text-sm font-bold text-slate-700 mb-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                <input type="text" name="mata_pelajaran" id="mata_pelajaran" value="{{ old('mata_pelajaran') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('mata_pelajaran') border-rose-300 ring-rose-500 @enderror" placeholder="Contoh: Pemrograman Web">
                @error('mata_pelajaran') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kompetensi_keahlian" class="block text-sm font-bold text-slate-700 mb-1">Kompetensi Keahlian</label>
                <input type="text" name="kompetensi_keahlian" id="kompetensi_keahlian" value="{{ old('kompetensi_keahlian') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" placeholder="Contoh: Rekayasa Perangkat Lunak">
            </div>

            <div>
                <label for="pangkat_golongan" class="block text-sm font-bold text-slate-700 mb-1">Pangkat / Golongan</label>
                <input type="text" name="pangkat_golongan" id="pangkat_golongan" value="{{ old('pangkat_golongan') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" placeholder="Contoh: Penata Muda Tk. I / III/b">
            </div>

            <div>
                <label for="jabatan" class="block text-sm font-bold text-slate-700 mb-1">Jabatan Fungsional</label>
                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" placeholder="Contoh: Guru Ahli Pertama">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Reset</button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Data Guru
            </button>
        </div>
    </form>
</div>
@endsection
