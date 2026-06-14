@extends('layouts.app')
@section('title', 'Input Rekomendasi')

@section('content')
<div class="mb-6">
    <a href="{{ route('evaluations.rekomendasis.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-base font-bold text-slate-800 flex items-center">
                    <i data-lucide="info" class="w-5 h-5 text-indigo-500 mr-2"></i> Info Evaluasi
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Guru yang Dinilai</p>
                    <p class="font-medium text-slate-900">{{ $evaluation->guru->nama }}</p>
                    <p class="text-sm text-slate-500">NIP. {{ $evaluation->guru->nip }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Asal Sekolah</p>
                    <p class="font-medium text-slate-900">{{ $evaluation->guru->school->nama }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Periode Evaluasi</p>
                    <p class="font-medium text-slate-900">{{ $evaluation->evaluationPeriod->nama }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Skor Rata-Rata Akhir</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $evaluation->rata_rata }}</p>
                </div>
                
                <hr class="border-slate-100 border-dashed my-4">
                
                <div class="bg-indigo-50 text-indigo-800 p-4 rounded-xl text-sm leading-relaxed border border-indigo-100">
                    <p class="font-bold mb-2 flex items-center"><i data-lucide="lightbulb" class="w-4 h-4 mr-1.5"></i> Panduan Rekomendasi</p>
                    Pemberian rekomendasi adalah analisis menyilang pada setiap indikator yang mencakup:
                    <ul class="list-disc ml-5 mt-2 space-y-1">
                        <li><strong>WHAT:</strong> Apa yang menjadi kekuatan dan kelemahan guru.</li>
                        <li><strong>WHY:</strong> Mengapa hal itu bisa terjadi.</li>
                        <li><strong>HOW:</strong> Bagaimana cara memperbaikinya.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800">Form Analisis & Rekomendasi</h2>
            </div>
            
            <form action="{{ route('evaluations.rekomendasis.store', $evaluation) }}" method="POST" class="p-8">
                @csrf
                
                <div class="space-y-6">
                    <!-- WHAT -->
                    <div>
                        <label for="what" class="block text-sm font-bold text-slate-700 mb-2 flex items-center">
                            <span class="bg-slate-800 text-white text-xs px-2 py-0.5 rounded mr-2">WHAT</span> 
                            Kekuatan dan Kelemahan Guru <span class="text-rose-500 ml-1">*</span>
                        </label>
                        <p class="text-xs text-slate-500 mb-2">Jelaskan secara spesifik kekuatan dan kelemahan guru berdasarkan hasil penilaian pada setiap indikator.</p>
                        <textarea name="what" id="what" rows="4" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('what') border-rose-300 ring-rose-500 @enderror">{{ old('what', $rekomendasi->what ?? '') }}</textarea>
                        @error('what') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- WHY -->
                    <div>
                        <label for="why" class="block text-sm font-bold text-slate-700 mb-2 flex items-center">
                            <span class="bg-slate-800 text-white text-xs px-2 py-0.5 rounded mr-2">WHY</span> 
                            Penyebab (Akar Masalah) <span class="text-rose-500 ml-1">*</span>
                        </label>
                        <p class="text-xs text-slate-500 mb-2">Mengapa kekuatan atau kelemahan tersebut bisa terjadi? Lakukan analisis penyebabnya.</p>
                        <textarea name="why" id="why" rows="4" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('why') border-rose-300 ring-rose-500 @enderror">{{ old('why', $rekomendasi->why ?? '') }}</textarea>
                        @error('why') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- HOW -->
                    <div>
                        <label for="how" class="block text-sm font-bold text-slate-700 mb-2 flex items-center">
                            <span class="bg-slate-800 text-white text-xs px-2 py-0.5 rounded mr-2">HOW</span> 
                            Solusi / Rencana Perbaikan <span class="text-rose-500 ml-1">*</span>
                        </label>
                        <p class="text-xs text-slate-500 mb-2">Bagaimana cara mempertahankan kekuatan dan memperbaiki kelemahan tersebut? Berikan langkah konkret.</p>
                        <textarea name="how" id="how" rows="4" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('how') border-rose-300 ring-rose-500 @enderror">{{ old('how', $rekomendasi->how ?? '') }}</textarea>
                        @error('how') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <hr class="border-slate-200">

                    <!-- KESIMPULAN REKOMENDASI -->
                    <div>
                        <label for="rekomendasi" class="block text-sm font-bold text-slate-700 mb-2">
                            Kesimpulan Rekomendasi Umum <span class="text-rose-500 ml-1">*</span>
                        </label>
                        <p class="text-xs text-slate-500 mb-2">Rumusan rekomendasi akhir secara menyeluruh untuk guru ini dalam periode berikutnya.</p>
                        <textarea name="rekomendasi" id="rekomendasi" rows="4" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('rekomendasi') border-rose-300 ring-rose-500 @enderror">{{ old('rekomendasi', $rekomendasi->rekomendasi ?? '') }}</textarea>
                        @error('rekomendasi') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Rekomendasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
