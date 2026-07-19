@extends('layouts.app')
@section('title', 'Tambah Jenis Dokumen')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <a href="{{ route('jenis-dokumens.index') }}" class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Tambah Jenis Dokumen</h2>
            <p class="text-sm text-slate-500 mt-1">Buat jenis dokumen baru dan petakan ke aspek penilaian.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <form action="{{ route('jenis-dokumens.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @csrf
            
            <div class="p-8 border-b border-slate-100">
                <div class="mb-6">
                    <label for="nama_jenis_dokumen" class="block text-sm font-bold text-slate-900 mb-2">Nama Jenis Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_jenis_dokumen" id="nama_jenis_dokumen" value="{{ old('nama_jenis_dokumen') }}" class="w-full text-sm border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 bg-slate-50" placeholder="Contoh: RPP, Modul Ajar, Laporan Kegiatan" required>
                    @error('nama_jenis_dokumen')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="p-8 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="link" class="w-4 h-4 mr-2 text-indigo-600"></i> Pemetaan Aspek Penilaian (Telaah Dokumen)
                </h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                    Centang aspek penilaian di bawah ini yang dapat dibuktikan oleh dokumen ini. Satu aspek hanya bisa dimiliki oleh satu jenis dokumen.
                </p>

                <div class="space-y-6">
                    @forelse($aspects as $indicatorId => $indicatorAspects)
                        @php $indicator = $indicatorAspects->first()->indicator; @endphp
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-700">{{ $indicator->kode }}</span>
                                <h4 class="text-sm font-bold text-slate-900">{{ $indicator->nama }}</h4>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($indicatorAspects as $aspect)
                                <label class="flex items-start gap-3 cursor-pointer group p-2 hover:bg-slate-50 rounded-lg transition-colors">
                                    <div class="flex items-center h-5 mt-0.5">
                                        <input type="checkbox" name="aspects[]" value="{{ $aspect->id }}" {{ in_array($aspect->id, old('aspects', [])) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                    </div>
                                    <div class="flex-1 text-sm">
                                        <span class="font-medium text-slate-700 group-hover:text-slate-900 block">{{ $aspect->aspek }}</span>
                                        @if($aspect->jenis_dokumen_id)
                                            <span class="text-xs text-rose-500 mt-1 block">
                                                <i data-lucide="alert-circle" class="w-3 h-3 inline"></i> Saat ini dipetakan ke: <b>{{ $aspect->jenisDokumen->nama_jenis_dokumen }}</b> (Akan dipindahkan jika dicentang)
                                            </span>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-500 text-sm bg-white rounded-xl border border-dashed border-slate-300">
                            Tidak ada aspek penilaian dengan metode telaah dokumen yang tersedia.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-white flex justify-end gap-3">
                <a href="{{ route('jenis-dokumens.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">Simpan Jenis Dokumen</button>
            </div>
        </form>
    </div>
    
    <div class="lg:col-span-1">
        <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
            <div class="flex items-center gap-3 mb-4 text-indigo-800">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i data-lucide="info" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold">Informasi Pemetaan</h3>
            </div>
            <p class="text-sm text-indigo-900/80 leading-relaxed mb-4">
                Memetakan aspek penilaian ke jenis dokumen akan mempermudah guru saat mengunggah bukti secara <b>General</b>. Saat guru memilih jenis dokumen ini, sistem akan otomatis mencentang aspek-aspek yang telah Anda petakan.
            </p>
            <p class="text-sm text-indigo-900/80 leading-relaxed">
                Relasi pemetaan adalah <b>One-to-Many</b>, artinya satu aspek telaah dokumen hanya bisa dimiliki oleh maksimal satu jenis dokumen.
            </p>
        </div>
    </div>
</div>
@endsection
