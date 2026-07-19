@extends('layouts.app')
@section('title', 'Detail Evaluasi Kinerja')

@section('content')
    <div class="mb-6">
        <a href="{{ route('evaluations.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Evaluasi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 h-full relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6">
                    @if($evaluation->status == 'approved')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Disetujui
                            Kepala Sekolah</span>
                    @elseif($evaluation->status == 'completed')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Menunggu
                            Review Kepala Sekolah</span>
                    @elseif($evaluation->status == 'in_progress')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Proses
                            Penilaian</span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Draft
                            / Belum Dimulai</span>
                    @endif
                </div>

                <h3 class="text-sm font-medium text-indigo-600 mb-1 uppercase tracking-wider">Informasi Penilaian</h3>
                <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ $evaluation->evaluationPeriod->nama }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Guru Yang Dinilai</p>
                        <p class="text-sm font-bold text-slate-900">{{ $evaluation->guru->nama }}</p>
                        <p class="text-xs text-slate-600 mt-0.5">NIP: {{ $evaluation->guru->nip ?? '-' }}</p>
                        <p class="text-xs text-slate-600 mt-1">Pangkat/Golongan: <span
                                class="font-medium text-slate-800">{{ $evaluation->guru->pangkatGolongan->nama ?? '-' }}
                                {{ isset($evaluation->guru->pangkatGolongan->golongan) ? '(' . $evaluation->guru->pangkatGolongan->golongan . ')' : '' }}</span>
                        </p>
                        <p class="text-xs text-slate-600 mt-1">Mapel: <span
                                class="font-medium text-slate-800">{{ $evaluation->guru->mataPelajaran->nama ?? $evaluation->guru->mata_pelajaran ?? '-' }}</span>
                        </p>
                        <p class="text-xs text-slate-600">Kelompok Mapel: <span
                                class="font-medium text-slate-800">{{ $evaluation->guru->mataPelajaran->kelompokMapel->nama_kelompok_mapel ?? '-' }}</span>
                        </p>
                        <p class="text-xs text-slate-600">Kompetensi Keahlian: <span
                                class="font-medium text-slate-800">{{ $evaluation->guru->kompetensiKeahlian->nama ?? '-' }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Asesor / Penilai</p>
                        <p class="text-sm font-bold text-slate-900">{{ $evaluation->penilai->nama }}</p>
                        <p class="text-xs text-slate-600 mt-0.5">NIP: {{ $evaluation->penilai->nip ?? '-' }}</p>
                        <p class="text-xs text-slate-600 mt-1">Pangkat/Golongan: <span
                                class="font-medium text-slate-800">{{ $evaluation->penilai->pangkatGolongan->nama ?? '-' }}
                                {{ isset($evaluation->penilai->pangkatGolongan->golongan) ? '(' . $evaluation->penilai->pangkatGolongan->golongan . ')' : '' }}</span>
                        </p>
                        <p class="text-xs text-slate-600">{{ $evaluation->penilai->jabatan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div
                class="bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl p-6 shadow-md border border-indigo-400 h-full text-white flex flex-col justify-between">
                <div>
                    <h3 class="text-indigo-100 font-medium mb-1">Hasil Evaluasi Akhir</h3>
                    <div class="flex items-baseline gap-2 mt-4">
                        <span class="text-5xl font-black">{{ $evaluation->rata_rata ?? '-' }}</span>
                        <span class="text-lg text-indigo-200 font-medium">/ 4.0</span>
                    </div>
                    <p class="text-sm text-indigo-100 mt-2">Total Skor: {{ $evaluation->total_skor ?? '-' }}</p>
                </div>

                <div class="mt-6 pt-6 border-t border-indigo-400/30">
                    <p class="text-xs text-indigo-200 mb-2">Progres Penilaian</p>
                    <div class="flex items-center gap-3">
                        <div class="w-full bg-indigo-900/40 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)]"
                                style="width: {{ $evaluation->progress }}%"></div>
                        </div>
                        <span class="text-sm font-bold">{{ $evaluation->progress }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($evaluation->catatan_kepala_sekolah)
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="message-square-quote" class="h-5 w-5 text-blue-600"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-900">Catatan Kepala Sekolah</h4>
                    <p class="text-sm text-blue-800 mt-2 leading-relaxed whitespace-pre-wrap">
                        {{ $evaluation->catatan_kepala_sekolah }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->isKepalaSekolah() && $evaluation->status == 'completed')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Review Kepala Sekolah</h3>
            <form action="{{ route('evaluations.approve', $evaluation) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="catatan_kepala_sekolah" class="block text-sm font-medium text-slate-700 mb-2">Catatan / Feedback
                        (Opsional)</label>
                    <textarea name="catatan_kepala_sekolah" id="catatan_kepala_sekolah" rows="3"
                        class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border"
                        placeholder="Berikan catatan mengenai hasil evaluasi ini..."></textarea>
                </div>
                <button type="submit"
                    class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors flex items-center shadow-sm">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Setujui Hasil Evaluasi
                </button>
            </form>
        </div>
    @endif

    @php
        $canEvaluate = (auth()->user()->isPenilai() && $evaluation->penilai_id === auth()->user()->penilai->id) ||
            (auth()->user()->isKepalaSekolah() && $evaluation->guru->school_id === auth()->user()->school_id);
    @endphp

    @if($canEvaluate && in_array($evaluation->status, ['in_progress', 'draft']))
        @if($evaluation->progress == 100)
            <div
                class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="text-base font-bold text-emerald-900">Penilaian Selesai</h4>
                    <p class="text-sm text-emerald-700 mt-1">Seluruh butir indikator telah dinilai. Anda dapat mengirimkan hasil
                        evaluasi ini ke Kepala Sekolah untuk direview.</p>
                </div>
                <form action="{{ route('evaluations.submit', $evaluation) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-emerald-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-colors shadow-sm flex items-center whitespace-nowrap">
                        <i data-lucide="send" class="w-4 h-4 mr-2"></i> Submit ke Kepala Sekolah
                    </button>
                </form>
            </div>
        @endif
    @endif

    @if(auth()->user()->isGuru() && in_array($evaluation->status, ['draft', 'in_progress']))
        <div
            class="bg-indigo-50 border border-indigo-200 rounded-2xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="text-base font-bold text-indigo-900">Upload Bukti Dokumen Lintas Indikator</h4>
                <p class="text-sm text-indigo-700 mt-1">Gunakan fitur ini untuk mengunggah 1 (satu) dokumen dan menautkannya ke
                    berbagai aspek penilaian pada beberapa indikator sekaligus.</p>
            </div>
            <a href="{{ route('evaluations.upload-general', $evaluation) }}"
                class="bg-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm flex items-center whitespace-nowrap">
                <i data-lucide="upload-cloud" class="w-4 h-4 mr-2"></i> Upload Bukti General
            </a>
        </div>
    @endif

    <!-- Rincian Penilaian Per Dimensi -->
    <div class="space-y-8">
        @foreach($dimensions as $dim)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center gap-3">
                    <div
                        class="h-8 w-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                        {{ $dim->urutan_romawi ?? $dim->urutan }}
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">{{ $dim->nama }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $dim->deskripsi }}</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($dim->indicators as $ind)
                        @php
                            $result = $resultsMap->get($ind->id);
                            $isCompleted = $result && $result->status == 'selesai';
                        @endphp
                        <div class="p-6 hover:bg-slate-50/50 transition-colors group">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h4 class="text-sm font-bold text-slate-900">{{ $ind->urutan_keseluruhan }}.
                                            {{ $ind->nama }}</h4>
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $ind->kode }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $ind->deskripsi }}</p>

                                    <div class="mt-4 flex gap-2">
                                        @if($ind->has_observasi) <span
                                            class="inline-flex items-center text-[11px] font-medium text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded"><i
                                        data-lucide="eye" class="w-3 h-3 mr-1"></i> Observasi</span> @endif
                                        @if($ind->has_telaah_dokumen) <span
                                            class="inline-flex items-center text-[11px] font-medium text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded"><i
                                        data-lucide="file-text" class="w-3 h-3 mr-1"></i> Telaah Dokumen</span> @endif
                                        @if($ind->has_wawancara) <span
                                            class="inline-flex items-center text-[11px] font-medium text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded"><i
                                        data-lucide="mic" class="w-3 h-3 mr-1"></i> Wawancara</span> @endif
                                    </div>
                                </div>

                                <div
                                    class="md:w-64 flex flex-col justify-center border-l md:border-l-slate-100 border-t md:border-t-transparent pt-4 md:pt-0 pl-0 md:pl-6">
                                    @if($isCompleted)
                                        <div class="mb-3">
                                            <span class="text-xs text-slate-500 mb-1 block">Level Capaian:</span>
                                            <div
                                                class="inline-flex items-center px-3 py-1 rounded-lg font-bold text-sm bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                Level {{ $result->level_capaian }}
                                            </div>
                                        </div>
                                        @if($canEvaluate && in_array($evaluation->status, ['draft', 'in_progress']))
                                            <a href="{{ route('evaluations.indicator', [$evaluation, $ind]) }}"
                                                class="inline-flex items-center text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                                <i data-lucide="edit" class="w-3 h-3 mr-1"></i> Edit Penilaian
                                            </a>
                                        @else
                                            <a href="{{ route('evaluations.indicator.show', [$evaluation, $ind]) }}"
                                                class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-slate-800">
                                                <i data-lucide="file-search" class="w-3 h-3 mr-1"></i> Lihat Kesimpulan
                                            </a>
                                        @endif
                                    @else
                                        <div class="mb-3">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-500">Belum
                                                Dinilai</span>
                                        </div>
                                        @if($canEvaluate && in_array($evaluation->status, ['draft', 'in_progress']))
                                            <a href="{{ route('evaluations.indicator', [$evaluation, $ind]) }}"
                                                class="inline-flex items-center justify-center w-full px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition-colors shadow-sm mb-2">
                                                Mulai Penilaian
                                            </a>
                                        @endif

                                        @if(auth()->user()->isGuru() && $ind->has_telaah_dokumen && in_array($evaluation->status, ['draft', 'in_progress']))
                                            <a href="{{ route('evaluations.upload', [$evaluation, $ind]) }}"
                                                class="inline-flex items-center justify-center w-full px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 text-sm font-medium rounded-xl hover:bg-indigo-100 transition-colors shadow-sm">
                                                <i data-lucide="upload-cloud" class="w-4 h-4 mr-2"></i> Upload Bukti
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

@endsection