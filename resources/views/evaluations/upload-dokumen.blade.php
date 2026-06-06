@extends('layouts.app')
@section('title', 'Upload Bukti Dokumen')

@section('content')
<div class="mb-6">
    <a href="{{ route('evaluations.show', $evaluation) }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Detail Evaluasi
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-w-4xl">
    <div class="px-8 py-6 border-b border-slate-100 bg-indigo-50/50">
        <div class="flex items-center gap-2 mb-2">
            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">{{ $indicator->kode }}</span>
            <h3 class="text-lg font-bold text-slate-900">{{ $indicator->nama }}</h3>
        </div>
        <p class="text-sm text-slate-600 leading-relaxed">{{ $indicator->deskripsi }}</p>
    </div>
    
    <div class="px-8 py-4 bg-amber-50 border-b border-amber-100 text-amber-800 text-sm flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 flex-shrink-0 text-amber-600 mt-0.5"></i>
        <p>Silakan unggah dokumen bukti (PDF/JPG/PNG, maksimal 5MB per file) yang sesuai dengan permintaan pada setiap aspek telaah dokumen di bawah ini. Anda dapat mengunggahnya satu per satu maupun sekaligus.</p>
    </div>

    <form action="{{ route('evaluations.upload.store', [$evaluation, $indicator]) }}" method="POST" enctype="multipart/form-data" class="p-8">
        @csrf
        
        <div class="space-y-6">
            @forelse($indicator->documentReviewAspects as $aspect)
                @php
                    $data = $result->documentReviewData->where('assessment_aspect_id', $aspect->id)->first();
                @endphp
                <div class="border border-slate-200 rounded-xl p-5 hover:border-indigo-300 transition-colors">
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Aspek {{ $aspect->nomor }}: {{ $aspect->aspek }}</h4>
                            @if($aspect->nama_dokumen)
                                <p class="text-xs text-slate-600 mb-3"><span class="font-semibold">Dokumen yang diminta:</span> {{ $aspect->nama_dokumen }}</p>
                            @endif
                            
                            @if($data && $data->file_path)
                                <div class="mt-3 inline-flex items-center px-3 py-2 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm">
                                    <i data-lucide="check-circle-2" class="w-4 h-4 mr-2"></i>
                                    <span>Sudah diunggah: <strong>{{ $data->original_filename ?? 'dokumen_terlampir' }}</strong></span>
                                </div>
                            @else
                                <div class="mt-2 inline-flex items-center text-xs font-medium text-rose-500 bg-rose-50 px-2 py-1 rounded border border-rose-100">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Belum ada dokumen
                                </div>
                            @endif
                        </div>
                        <div class="w-72 flex-shrink-0">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pilih File Baru (opsional)</label>
                            <input type="file" name="dokumen[{{ $aspect->id }}]" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                    Tidak ada aspek telaah dokumen untuk indikator ini.
                </div>
            @endforelse
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-500">File yang diunggah akan otomatis menimpa file lama jika ada.</span>
            <div class="flex gap-3">
                <a href="{{ route('evaluations.show', $evaluation) }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                    <i data-lucide="upload" class="w-4 h-4 mr-2"></i> Unggah Dokumen
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
