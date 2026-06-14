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
                <label for="mata_pelajaran_id" class="block text-sm font-bold text-slate-700 mb-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('mata_pelajaran_id') border-rose-300 ring-rose-500 @enderror select2">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mataPelajarans as $item)
                        <option value="{{ $item->id }}" {{ old('mata_pelajaran_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                    @endforeach
                </select>
                @error('mata_pelajaran_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kelompok_mapel" class="block text-sm font-bold text-slate-700 mb-1">Kelompok Mapel</label>
                <input type="text" id="kelompok_mapel" placeholder="Pilih Mata Pelajaran dahulu" class="block w-full rounded-xl border-slate-300 bg-slate-100 shadow-sm sm:text-sm p-2.5 border text-slate-500" disabled readonly>
            </div>

            <div>
                <label for="kompetensi_keahlian_id" class="block text-sm font-bold text-slate-700 mb-1">Kompetensi Keahlian</label>
                <select name="kompetensi_keahlian_id" id="kompetensi_keahlian_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border select2">
                    <option value="">-- Pilih Kompetensi Keahlian --</option>
                    @foreach($kompetensiKeahlians as $item)
                        <option value="{{ $item->id }}" {{ old('kompetensi_keahlian_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="pangkat_golongan_id" class="block text-sm font-bold text-slate-700 mb-1">Pangkat / Golongan</label>
                <select name="pangkat_golongan_id" id="pangkat_golongan_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border select2">
                    <option value="">-- Pilih Pangkat/Golongan --</option>
                    @foreach($pangkatGolongans as $item)
                        <option value="{{ $item->id }}" {{ old('pangkat_golongan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="jabatan_fungsional_id" class="block text-sm font-bold text-slate-700 mb-1">Jabatan Fungsional</label>
                <select name="jabatan_fungsional_id" id="jabatan_fungsional_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border select2">
                    <option value="">-- Pilih Jabatan Fungsional --</option>
                    @foreach($jabatanFungsionals as $item)
                        <option value="{{ $item->id }}" {{ old('jabatan_fungsional_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                    @endforeach
                </select>
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

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });

        const mapelData = {
            @foreach($mataPelajarans as $item)
                "{{ $item->id }}": "{{ $item->kelompokMapel ? $item->kelompokMapel->nama_kelompok_mapel : '-' }}",
            @endforeach
        };

        $('#mata_pelajaran_id').on('change', function() {
            const val = $(this).val();
            if (val && mapelData[val]) {
                $('#kelompok_mapel').val(mapelData[val]);
            } else {
                $('#kelompok_mapel').val('');
            }
        });

        // Trigger change on load if selected
        $('#mata_pelajaran_id').trigger('change');
    });
</script>
@endsection
