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
                <label for="kepala_sekolah" class="block text-sm font-bold text-slate-700 mb-2">Kepala Sekolah <span class="text-xs text-slate-400 font-normal ml-1">(Bisa diisi belakangan)</span></label>
                <select name="kepala_sekolah" id="kepala_sekolah" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border select2 @error('kepala_sekolah') border-rose-300 ring-rose-500 @enderror">
                    <option value="">Pilih Kepala Sekolah...</option>
                    @foreach($kepsekUsers as $user)
                        <option value="{{ $user->name }}" {{ old('kepala_sekolah') == $user->name ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('kepala_sekolah') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="alamat" class="block text-sm font-bold text-slate-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('alamat') border-rose-300 ring-rose-500 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="provinsi_id" class="block text-sm font-bold text-slate-700 mb-1">Provinsi <span class="text-rose-500">*</span></label>
                <select name="provinsi_id" id="provinsi_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('provinsi_id') border-rose-300 ring-rose-500 @enderror select2">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach($provinsis as $provinsi)
                        <option value="{{ $provinsi->id }}" {{ old('provinsi_id') == $provinsi->id ? 'selected' : '' }}>{{ $provinsi->nama }}</option>
                    @endforeach
                </select>
                @error('provinsi_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kabupaten_id" class="block text-sm font-bold text-slate-700 mb-1">Kabupaten/Kota <span class="text-rose-500">*</span></label>
                <select name="kabupaten_id" id="kabupaten_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('kabupaten_id') border-rose-300 ring-rose-500 @enderror select2">
                    <option value="">-- Pilih Kabupaten --</option>
                </select>
                @error('kabupaten_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
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

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });

        $('#provinsi_id').on('change', function() {
            var provinsiId = $(this).val();
            if(provinsiId) {
                $.ajax({
                    url: '/api/kabupatens/' + provinsiId,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#kabupaten_id').empty();
                        $('#kabupaten_id').append('<option value="">-- Pilih Kabupaten --</option>');
                        $.each(data, function(key, value) {
                            $('#kabupaten_id').append('<option value="'+ value.id +'">'+ value.nama +'</option>');
                        });
                    }
                });
            } else {
                $('#kabupaten_id').empty();
                $('#kabupaten_id').append('<option value="">-- Pilih Kabupaten --</option>');
            }
        });
    });
</script>
@endsection
