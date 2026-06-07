@extends('layouts.app')
@section('title', 'Tambah Kabupaten')

@section('content')
<div class="mb-6">
    <a href="{{ route('kabupatens.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Data Kabupaten
    </a>
</div>

<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">Form Tambah Kabupaten</h2>
    </div>
    
    <div class="p-8">
        <form action="{{ route('kabupatens.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="provinsi_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Provinsi <span class="text-red-500">*</span></label>
                <select name="provinsi_id" id="provinsi_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('provinsi_id') border-red-300 ring-red-500 @enderror">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach($provinsis as $provinsi)
                        <option value="{{ $provinsi->id }}" {{ old('provinsi_id') == $provinsi->id ? 'selected' : '' }}>{{ $provinsi->nama }}</option>
                    @endforeach
                </select>
                @error('provinsi_id')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kabupaten / Kota <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('nama') border-red-300 ring-red-500 @enderror" 
                    placeholder="Contoh: Kota Surabaya">
                @error('nama')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center shadow-sm">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Kabupaten
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
