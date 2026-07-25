@extends('layouts.app')
@section('title', 'Tambah Data Asesor')

@section('content')
<div class="mb-6">
    <a href="{{ route('penilais.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Asesor
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-3xl">
    <div class="px-8 py-6 border-b border-slate-100 flex items-start gap-4 bg-indigo-50/30">
        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
            <i data-lucide="user-check" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900">Form Tambah Asesor / Penilai</h3>
            @if(isset($guru))
            <p class="text-sm text-emerald-600 mt-1 font-medium"><i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i> Mode Penambahan Profil Ganda. Sebagian data telah diisi otomatis dari Profil Guru.</p>
            @else
            <p class="text-sm text-slate-600 mt-1">Sistem akan secara otomatis membuatkan akun pengguna dengan <span class="font-bold text-indigo-700">password default menggunakan NIP atau 'password123'</span>.</p>
            @endif
        </div>
    </div>
    
    <form action="{{ route('penilais.store') }}" method="POST" class="p-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label for="school_id" class="block text-sm font-bold text-slate-700 mb-1">Penugasan ke Sekolah <span class="text-rose-500">*</span></label>
                <select name="school_id" id="school_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('school_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Sekolah Tujuan Penilaian --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', isset($guru) ? $guru->school_id : '') == $school->id ? 'selected' : '' }}>{{ $school->npsn }} - {{ $school->nama }}</option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap (beserta gelar) <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', isset($guru) ? $guru->nama : '') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('nama') border-rose-300 ring-rose-500 @enderror">
                @error('nama') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email (Untuk Login) <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', (isset($guru) && $guru->user) ? $guru->user->email : '') }}" required {{ isset($guru) ? 'readonly' : '' }} class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border {{ isset($guru) ? 'bg-slate-100 text-slate-500' : '' }} @error('email') border-rose-300 ring-rose-500 @enderror">
                @if(isset($guru))
                    <p class="mt-1 text-xs text-emerald-600">Email dikunci untuk menghubungkan dengan akun yang sama.</p>
                @endif
                @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nip" class="block text-sm font-bold text-slate-700 mb-1">NIP (Opsional)</label>
                <input type="text" name="nip" id="nip" value="{{ old('nip', isset($guru) ? $guru->nip : '') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>

            <div>
                <label for="jabatan" class="block text-sm font-bold text-slate-700 mb-1">Jabatan Asesor <span class="text-rose-500">*</span></label>
                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('jabatan') border-rose-300 ring-rose-500 @enderror" placeholder="Contoh: Pengawas / Guru Senior">
                @error('jabatan') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="pangkat_golongan_id" class="block text-sm font-bold text-slate-700 mb-1">Pangkat/Golongan (Opsional)</label>
                <select name="pangkat_golongan_id" id="pangkat_golongan_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('pangkat_golongan_id') border-rose-300 ring-rose-500 @enderror">
                    <option value="">-- Pilih Pangkat/Golongan --</option>
                    @foreach($pangkatGolongans as $pg)
                        <option value="{{ $pg->id }}" {{ old('pangkat_golongan_id', isset($guru) ? $guru->pangkat_golongan_id : '') == $pg->id ? 'selected' : '' }}>{{ $pg->nama }} ({{ $pg->golongan }})</option>
                    @endforeach
                </select>
                @error('pangkat_golongan_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="instansi" class="block text-sm font-bold text-slate-700 mb-1">Instansi Asal <span class="text-rose-500">*</span></label>
                <input type="text" name="instansi" id="instansi" value="{{ old('instansi') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border @error('instansi') border-rose-300 ring-rose-500 @enderror" placeholder="Contoh: Cabang Dinas Wilayah 1">
                @error('instansi') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="no_telepon" class="block text-sm font-bold text-slate-700 mb-1">Nomor Telepon/HP</label>
                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', isset($guru) ? $guru->no_telepon : '') }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
            </div>

            <div class="md:col-span-2 mt-4 pt-4 border-t border-slate-100">
                <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Guru yang Dapat Dinilai oleh Asesor Ini <span class="text-xs font-normal text-slate-500">(Bisa pilih lebih dari 1)</span></label>
                <p class="text-xs text-slate-500 mb-3">Cari dan pilih guru menggunakan kotak pencarian di bawah ini, lalu klik "Tambah ke Daftar".</p>
                
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center mb-4">
                    <div class="flex-1 w-full">
                        <select id="guru_search_select" class="block w-full rounded-xl border-slate-300 shadow-sm sm:text-sm select2">
                            <option value="">-- Cari Nama atau NIP Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" data-nama="{{ $guru->nama }}" data-nip="{{ $guru->nip ?? '-' }}" data-sekolah="{{ $guru->school->nama ?? '-' }}">
                                    {{ $guru->nama }} (NIP: {{ $guru->nip ?? '-' }}) - {{ $guru->school->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="btn_add_guru" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm transition-colors flex items-center shrink-0">
                        <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Tambah ke Daftar
                    </button>
                </div>

                <div id="hidden_guru_inputs"></div>

                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <table class="w-full text-sm text-left text-slate-700 whitespace-nowrap">
                        <thead class="text-xs text-slate-600 uppercase bg-slate-100 border-b border-slate-200 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">No</th>
                                <th class="px-6 py-4 font-semibold">Nama Guru</th>
                                <th class="px-6 py-4 font-semibold">NIP</th>
                                <th class="px-6 py-4 font-semibold">Sekolah</th>
                                <th class="px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="selected_gurus_tbody" class="divide-y divide-slate-100">
                            <!-- Diisi oleh JS -->
                        </tbody>
                    </table>
                    <div id="pagination_controls" class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-600">
                        <!-- Diisi oleh JS -->
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Reset</button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Data Asesor
            </button>
        </div>
    </form>
</div>

@php
    $oldGuruIds = old('guru_ids', []);
    $initialGurus = $gurus->whereIn('id', $oldGuruIds)->values()->map(function($g) {
        return [
            'id' => $g->id,
            'nama' => $g->nama,
            'nip' => $g->nip ?? '-',
            'sekolah' => $g->school->nama ?? '-'
        ];
    });
@endphp

<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        placeholder: '-- Cari Nama atau NIP Guru --'
    });

    let selectedGurus = @json($initialGurus);
    let currentPage = 1;
    const itemsPerPage = 5;

    function renderTable() {
        const hiddenContainer = $('#hidden_guru_inputs');
        hiddenContainer.empty();
        selectedGurus.forEach(g => {
            hiddenContainer.append(`<input type="hidden" name="guru_ids[]" value="${g.id}">`);
        });

        const tbody = $('#selected_gurus_tbody');
        tbody.empty();

        if (selectedGurus.length === 0) {
            tbody.append(`<tr><td colspan="5" class="px-6 py-4 text-center">Belum ada guru yang dipilih ke dalam daftar.</td></tr>`);
            $('#pagination_controls').html(`<span class="text-slate-400">Total: 0 guru</span>`);
            return;
        }

        const totalPages = Math.ceil(selectedGurus.length / itemsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = Math.min(startIdx + itemsPerPage, selectedGurus.length);
        const paginatedItems = selectedGurus.slice(startIdx, endIdx);

        paginatedItems.forEach((g, idx) => {
            tbody.append(`
                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-center font-medium">${startIdx + idx + 1}</td>
                    <td class="px-6 py-4">${g.nama}</td>
                    <td class="px-6 py-4">${g.nip}</td>
                    <td class="px-6 py-4"><span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-xs font-medium">${g.sekolah}</span></td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" onclick="removeGuru(${g.id})" class="text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors" title="Hapus">
                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        let prevDisabled = currentPage === 1 ? 'disabled class="px-2.5 py-1 rounded border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed"' : 'class="px-2.5 py-1 rounded border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium cursor-pointer" onclick="changePage(-1)"';
        let nextDisabled = currentPage === totalPages ? 'disabled class="px-2.5 py-1 rounded border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed"' : 'class="px-2.5 py-1 rounded border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium cursor-pointer" onclick="changePage(1)"';

        $('#pagination_controls').html(`
            <span>Menampilkan <strong class="font-bold text-slate-800">${startIdx + 1} - ${endIdx}</strong> dari <strong class="font-bold text-slate-800">${selectedGurus.length}</strong> guru</span>
            <div class="flex items-center gap-1.5">
                <button type="button" ${prevDisabled}>&laquo; Sebelumnya</button>
                <span class="px-2 font-bold text-indigo-600">Hal. ${currentPage} / ${totalPages}</span>
                <button type="button" ${nextDisabled}>Selanjutnya &raquo;</button>
            </div>
        `);
    }

    $('#btn_add_guru').on('click', function() {
        const val = $('#guru_search_select').val();
        if (!val) {
            alert('Silakan cari dan pilih guru terlebih dahulu.');
            return;
        }

        if (selectedGurus.some(g => g.id == val)) {
            alert('Guru tersebut sudah ada di dalam daftar.');
            return;
        }

        const selectedOption = $('#guru_search_select').find('option:selected');
        selectedGurus.push({
            id: parseInt(val),
            nama: selectedOption.data('nama'),
            nip: selectedOption.data('nip'),
            sekolah: selectedOption.data('sekolah')
        });

        $('#guru_search_select').val('').trigger('change');
        currentPage = Math.ceil(selectedGurus.length / itemsPerPage);
        renderTable();
    });

    window.removeGuru = function(id) {
        selectedGurus = selectedGurus.filter(g => g.id !== id);
        renderTable();
    };

    window.changePage = function(dir) {
        currentPage += dir;
        renderTable();
    };

    renderTable();
});
</script>
@endsection
