<!-- Form Tambah Aspek -->
<div class="bg-slate-50 border border-slate-200 p-5 rounded-xl mb-6">
    <h4 class="text-sm font-bold text-slate-800 mb-3">Tambah Aspek {{ $metodeLabel }} Baru</h4>
    <form action="{{ route('indicators.aspects.store', ['indicator' => $indicator->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="indicator_id" value="{{ $indicator->id }}">
        <input type="hidden" name="metode" value="{{ $metode }}">
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
            <div class="md:col-span-2">
                <input type="number" name="nomor" placeholder="No" value="{{ count($aspects) + 1 }}" required min="1" class="w-full text-sm border-slate-300 rounded-lg p-2.5">
            </div>
            <div class="md:col-span-10">
                <textarea name="aspek" rows="2" placeholder="Deskripsi aspek penilaian..." required class="w-full text-sm border-slate-300 rounded-lg p-2.5"></textarea>
            </div>
            @if($metode == 'telaah_dokumen')
            <div class="md:col-span-12">
                <input type="text" name="nama_dokumen" placeholder="Nama/Bukti Dokumen yang diminta" class="w-full text-sm border-slate-300 rounded-lg p-2.5">
            </div>
            @endif
            @if($metode == 'wawancara')
            <div class="md:col-span-12 bg-white p-3 rounded-lg border border-slate-200">
                <label class="block text-xs font-bold text-slate-700 mb-2">Target Responden Wawancara (Centang yang sesuai):</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="kepala_wakil" checked class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Kepala Sekolah / Wakil
                    </label>
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="kepala_kompetensi" checked class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Kajur / Kapro
                    </label>
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="guru" checked class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Guru
                    </label>
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="siswa" checked class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Siswa
                    </label>
                </div>
            </div>
            @endif
        </div>
        <div class="mt-3 text-right">
            <button type="submit" class="text-xs font-semibold text-white bg-slate-800 hover:bg-slate-900 px-4 py-2 rounded-lg transition-colors">Tambah Aspek</button>
        </div>
    </form>
</div>

<!-- Daftar Aspek -->
<div class="space-y-4">
    @forelse($aspects as $aspect)
    <div class="border border-slate-200 p-4 rounded-xl">
        <form action="{{ route('indicators.aspects.update', ['indicator' => $indicator->id, 'aspect' => $aspect->id]) }}" method="POST" class="flex flex-col gap-3">
            @csrf
            @method('PUT')
            
            <div class="flex gap-3">
                <input type="number" name="nomor" value="{{ $aspect->nomor }}" required class="w-16 text-sm border-slate-300 rounded-lg p-2">
                <textarea name="aspek" rows="2" required class="flex-1 text-sm border-slate-300 rounded-lg p-2">{{ $aspect->aspek }}</textarea>
            </div>
            
            @if($metode == 'telaah_dokumen')
            <div class="pl-19">
                <input type="text" name="nama_dokumen" value="{{ $aspect->nama_dokumen }}" placeholder="Nama/Bukti Dokumen" class="w-full text-sm border-slate-300 rounded-lg p-2">
            </div>
            @endif

            @if($metode == 'wawancara')
            @php $targets = $aspect->target_responden_list; @endphp
            <div class="pl-19 bg-slate-50 p-3 rounded-lg border border-slate-200 mt-1">
                <label class="block text-xs font-bold text-slate-700 mb-2">Target Responden Wawancara:</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="kepala_wakil" {{ in_array('kepala_wakil', $targets) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Kepala Sekolah / Wakil
                    </label>
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="kepala_kompetensi" {{ in_array('kepala_kompetensi', $targets) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Kajur / Kapro
                    </label>
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="guru" {{ in_array('guru', $targets) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Guru
                    </label>
                    <label class="inline-flex items-center text-xs text-slate-700 font-medium cursor-pointer">
                        <input type="checkbox" name="target_responden[]" value="siswa" {{ in_array('siswa', $targets) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 mr-1.5 focus:ring-indigo-500"> Siswa
                    </label>
                </div>
            </div>
            @endif
            
            <div class="flex justify-between items-center pl-19 mt-1">
                <span class="text-xs text-slate-400">ID: {{ $aspect->id }}</span>
                <div class="flex gap-2">
                    <button type="submit" class="text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors">Simpan Edit</button>
                    </form>
                    
                    <form action="{{ route('indicators.aspects.destroy', ['indicator' => $indicator->id, 'aspect' => $aspect->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menghapus aspek penilaian ini? Jika sudah digunakan oleh asesor, penghapusan akan digagalkan oleh sistem.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    </form>
                </div>
            </div>
    </div>
    @empty
    <div class="text-center py-6 text-slate-500 text-sm bg-slate-50 rounded-xl border border-dashed border-slate-300">
        Belum ada aspek untuk metode ini. Silakan tambahkan.
    </div>
    @endforelse
</div>
