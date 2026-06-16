@extends('layouts.app')
@section('title', isset($kepala_sekolah) ? 'Edit Kepala Sekolah' : 'Tambah Kepala Sekolah')

@section('content')
<div class="mb-6">
    <a href="{{ route('kepala-sekolahs.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-3xl">
    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">{{ isset($kepala_sekolah) ? 'Edit Data Kepala Sekolah' : 'Tambah Kepala Sekolah Baru' }}</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi informasi akun dan profil Kepala Sekolah.</p>
    </div>
    
    <form action="{{ isset($kepala_sekolah) ? route('kepala-sekolahs.update', $kepala_sekolah) : route('kepala-sekolahs.store') }}" method="POST" class="p-8">
        @csrf
        @if(isset($kepala_sekolah))
            @method('PUT')
        @endif
        
        <div class="space-y-6">
            <!-- Data Login -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Informasi Login</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $kepala_sekolah->name ?? '') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('name') border-rose-300 ring-rose-500 @enderror">
                        @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" required value="{{ old('email', $kepala_sekolah->email ?? '') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('email') border-rose-300 ring-rose-500 @enderror">
                        @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password {!! !isset($kepala_sekolah) ? '<span class="text-rose-500">*</span>' : '<span class="text-xs text-slate-400 font-normal ml-1">(Kosongkan jika tidak diubah)</span>' !!}</label>
                        <input type="password" name="password" id="password" {{ !isset($kepala_sekolah) ? 'required' : '' }} class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('password') border-rose-300 ring-rose-500 @enderror">
                        @error('password') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-bold text-slate-700 mb-2">Status Akun <span class="text-rose-500">*</span></label>
                        <select name="is_active" id="is_active" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('is_active') border-rose-300 ring-rose-500 @enderror">
                            <option value="1" {{ old('is_active', isset($kepala_sekolah) ? $kepala_sekolah->is_active : 1) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', isset($kepala_sekolah) ? $kepala_sekolah->is_active : 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Data Profil -->
            <div class="pt-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Profil Kepegawaian</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nip" class="block text-sm font-bold text-slate-700 mb-2">NIP</label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $kepala_sekolah->kepalaSekolah->nip ?? '') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('nip') border-rose-300 ring-rose-500 @enderror">
                        @error('nip') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pangkat_golongan_id" class="block text-sm font-bold text-slate-700 mb-2">Pangkat / Golongan</label>
                        <select name="pangkat_golongan_id" id="pangkat_golongan_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border select2 @error('pangkat_golongan_id') border-rose-300 ring-rose-500 @enderror">
                            <option value="">Pilih Pangkat/Golongan...</option>
                            @foreach($pangkatGolongans as $pangkat)
                                <option value="{{ $pangkat->id }}" {{ old('pangkat_golongan_id', $kepala_sekolah->kepalaSekolah->pangkat_golongan_id ?? '') == $pangkat->id ? 'selected' : '' }}>
                                    {{ $pangkat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('pangkat_golongan_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-sm font-bold text-slate-700 mb-2">No. Telepon / HP</label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $kepala_sekolah->kepalaSekolah->no_telepon ?? '') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('no_telepon') border-rose-300 ring-rose-500 @enderror">
                        @error('no_telepon') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="school_id" class="block text-sm font-bold text-slate-700 mb-2">Unit Kerja (Sekolah) <span class="text-xs text-slate-400 font-normal ml-1">(Bisa diisi belakangan)</span></label>
                        <select name="school_id" id="school_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border select2 @error('school_id') border-rose-300 ring-rose-500 @enderror">
                            <option value="">Pilih Sekolah...</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $kepala_sekolah->school_id ?? '') == $school->id ? 'selected' : '' }}>
                                    {{ $school->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('kepala-sekolahs.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'classic',
            width: '100%'
        });
    });
</script>
@endpush
