<?php
$masters = [
    'mata-pelajarans' => 'Mata Pelajaran',
    'kompetensi-keahlians' => 'Kompetensi Keahlian',
    'pangkat-golongans' => 'Pangkat / Golongan',
    'jabatan-fungsionals' => 'Jabatan Fungsional'
];

foreach ($masters as $folder => $title) {
    if(!is_dir('resources/views/' . $folder)) {
        mkdir('resources/views/' . $folder, 0777, true);
    }
    
    // Index
    $index = "
@extends('layouts.app')
@section('title', 'Master $title')

@section('content')
<div class=\"mb-6 flex justify-between items-center\">
    <div>
        <h2 class=\"text-2xl font-bold text-slate-800\">Master Data $title</h2>
    </div>
    <a href=\"{{ route('$folder.create') }}\" class=\"bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center shadow-sm\">
        <i data-lucide=\"plus\" class=\"w-4 h-4 mr-2\"></i> Tambah Data
    </a>
</div>

<div class=\"bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden\">
    <div class=\"overflow-x-auto\">
        <table class=\"w-full text-sm text-left\">
            <thead class=\"text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200\">
                <tr>
                    <th class=\"px-6 py-4\">ID</th>
                    <th class=\"px-6 py-4\">Nama $title</th>
                    <th class=\"px-6 py-4 text-right\">Aksi</th>
                </tr>
            </thead>
            <tbody class=\"divide-y divide-slate-200\">
                @forelse(\$data as \$item)
                <tr class=\"hover:bg-slate-50 transition-colors\">
                    <td class=\"px-6 py-4 font-medium text-slate-900\">{{ \$item->id }}</td>
                    <td class=\"px-6 py-4\">{{ \$item->nama }}</td>
                    <td class=\"px-6 py-4 text-right\">
                        <div class=\"flex justify-end gap-2\">
                            <a href=\"{{ route('$folder.edit', \$item) }}\" class=\"p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors\" title=\"Edit\">
                                <i data-lucide=\"edit-2\" class=\"w-4 h-4\"></i>
                            </a>
                            <form action=\"{{ route('$folder.destroy', \$item) }}\" method=\"POST\" class=\"inline\" onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\">
                                @csrf
                                @method('DELETE')
                                <button type=\"submit\" class=\"p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors\" title=\"Hapus\">
                                    <i data-lucide=\"trash-2\" class=\"w-4 h-4\"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan=\"3\" class=\"px-6 py-8 text-center text-slate-500\">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(\$data->hasPages())
    <div class=\"px-6 py-4 border-t border-slate-200\">
        {{ \$data->links() }}
    </div>
    @endif
</div>
@endsection
";
    file_put_contents('resources/views/' . $folder . '/index.blade.php', $index);

    // Create
    $create = "
@extends('layouts.app')
@section('title', 'Tambah $title')

@section('content')
<div class=\"mb-6\">
    <a href=\"{{ route('$folder.index') }}\" class=\"inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors\">
        <i data-lucide=\"arrow-left\" class=\"w-4 h-4 mr-1\"></i> Kembali
    </a>
</div>

<div class=\"max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden\">
    <div class=\"px-8 py-6 border-b border-slate-100 bg-slate-50\">
        <h2 class=\"text-xl font-bold text-slate-800\">Form Tambah $title</h2>
    </div>
    
    <div class=\"p-8\">
        <form action=\"{{ route('$folder.store') }}\" method=\"POST\">
            @csrf
            
            <div class=\"mb-6\">
                <label for=\"nama\" class=\"block text-sm font-semibold text-slate-700 mb-2\">Nama $title <span class=\"text-red-500\">*</span></label>
                <input type=\"text\" name=\"nama\" id=\"nama\" value=\"{{ old('nama') }}\" required
                    class=\"block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('nama') border-red-300 ring-red-500 @enderror\">
                @error('nama')
                    <p class=\"mt-2 text-sm text-red-600 font-medium\">{{ \$message }}</p>
                @enderror
            </div>

            <div class=\"pt-4 border-t border-slate-100 flex justify-end\">
                <button type=\"submit\" class=\"bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center shadow-sm\">
                    <i data-lucide=\"save\" class=\"w-4 h-4 mr-2\"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
";
    file_put_contents('resources/views/' . $folder . '/create.blade.php', $create);

    // Edit
    $edit = "
@extends('layouts.app')
@section('title', 'Edit $title')

@section('content')
<div class=\"mb-6\">
    <a href=\"{{ route('$folder.index') }}\" class=\"inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors\">
        <i data-lucide=\"arrow-left\" class=\"w-4 h-4 mr-1\"></i> Kembali
    </a>
</div>

<div class=\"max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden\">
    <div class=\"px-8 py-6 border-b border-slate-100 bg-slate-50\">
        <h2 class=\"text-xl font-bold text-slate-800\">Form Edit $title</h2>
    </div>
    
    <div class=\"p-8\">
        <form action=\"{{ route('$folder.update', \$model) }}\" method=\"POST\">
            @csrf
            @method('PUT')
            
            <div class=\"mb-6\">
                <label for=\"nama\" class=\"block text-sm font-semibold text-slate-700 mb-2\">Nama $title <span class=\"text-red-500\">*</span></label>
                <input type=\"text\" name=\"nama\" id=\"nama\" value=\"{{ old('nama', \$model->nama) }}\" required
                    class=\"block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('nama') border-red-300 ring-red-500 @enderror\">
                @error('nama')
                    <p class=\"mt-2 text-sm text-red-600 font-medium\">{{ \$message }}</p>
                @enderror
            </div>

            <div class=\"pt-4 border-t border-slate-100 flex justify-end\">
                <button type=\"submit\" class=\"bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center shadow-sm\">
                    <i data-lucide=\"save\" class=\"w-4 h-4 mr-2\"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
";
    file_put_contents('resources/views/' . $folder . '/edit.blade.php', $edit);
}
