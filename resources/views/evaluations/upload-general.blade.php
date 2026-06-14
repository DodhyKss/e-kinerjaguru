@extends('layouts.app')
@section('title', 'Upload Bukti General')

@section('content')
<div class="mb-6">
    <a href="{{ route('evaluations.show', $evaluation) }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Detail Evaluasi
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
    <div class="flex items-start gap-4 mb-6">
        <div class="h-12 w-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
            <i data-lucide="upload-cloud" class="h-6 w-6"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Upload Bukti Dokumen Lintas Indikator</h2>
            <p class="text-sm text-slate-500 mt-1">Unggah 1 file dokumen lalu pilih indikator dan aspek penilaian apa saja yang dapat dinilai menggunakan dokumen ini.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evaluations.upload-general.store', $evaluation) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-8">
            <label class="block text-sm font-bold text-slate-900 mb-2">Pilih File Dokumen Bukti <span class="text-red-500">*</span></label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-indigo-500 transition-colors bg-slate-50">
                <div class="space-y-1 text-center">
                    <i data-lucide="file-up" class="mx-auto h-12 w-12 text-slate-400"></i>
                    <div class="flex text-sm text-slate-600 justify-center">
                        <label for="dokumen" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none px-2 py-1">
                            <span>Upload sebuah file</span>
                            <input id="dokumen" name="dokumen" type="file" class="sr-only" required accept=".pdf,.png,.jpg,.jpeg">
                        </label>
                    </div>
                    <p class="text-xs text-slate-500">PDF, PNG, JPG maksimal 5MB</p>
                    <p id="file-name-display" class="text-sm font-bold text-indigo-600 mt-2 hidden"></p>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-bold text-slate-900 mb-4">Pilih Aspek Penilaian Terkait (Pilih lebih dari satu) <span class="text-red-500">*</span></label>
            
            <div class="grid grid-cols-1 gap-6">
                @foreach($indicators as $ind)
                    @if($ind->documentReviewAspects->count() > 0)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">{{ $ind->kode }}</span>
                            <h4 class="text-sm font-bold text-slate-900">{{ $ind->nama }}</h4>
                        </div>
                        
                        <div class="space-y-3 pl-2">
                            @foreach($ind->documentReviewAspects as $aspect)
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="aspect_ids[]" value="{{ $aspect->id }}" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 mt-0.5">
                                </div>
                                <div class="text-sm">
                                    <span class="font-medium text-slate-700 group-hover:text-slate-900 transition-colors">{{ $aspect->aspek }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('evaluations.show', $evaluation) }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm inline-flex items-center">
                <i data-lucide="upload-cloud" class="w-4 h-4 mr-2"></i> Unggah & Simpan Mapping
            </button>
        </div>
    </form>
</div>

<script>
    // Script untuk menampilkan nama file yang dipilih
    document.getElementById('dokumen').addEventListener('change', function(e) {
        const display = document.getElementById('file-name-display');
        if (e.target.files.length > 0) {
            display.textContent = 'File terpilih: ' + e.target.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    });
</script>
@endsection
