<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\KelompokMapel;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\MataPelajaran::query();
        
        // Add specific eager loads or orders
        if ('MataPelajaran' === 'MataPelajaran') {
            $query->with('kelompokMapel')->latest();
        } elseif ('MataPelajaran' === 'Kabupaten') {
            $query->with('provinsi')->latest();
        } elseif ('MataPelajaran' === 'KelompokMapel') {
            $query->orderBy('nama_kelompok_mapel');
        } else {
            $query->latest();
        }

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $data = $query->paginate(10)->withQueryString();
        $allData = \App\Models\MataPelajaran::orderBy('nama')->get(['id', 'nama']);

        return view('mata-pelajarans.index', compact('data', 'allData'));
    }

    public function create()
    {
        $kelompokMapels = KelompokMapel::orderBy('nama_kelompok_mapel')->get();
        return view('mata-pelajarans.create', compact('kelompokMapels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:mata_pelajarans,nama',
            'kelompok_mapel_id' => 'nullable|exists:kelompok_mapels,id',
        ]);
        MataPelajaran::create($validated);
        return redirect()->route('mata-pelajarans.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        $kelompokMapels = KelompokMapel::orderBy('nama_kelompok_mapel')->get();
        return view('mata-pelajarans.edit', ['model' => $mataPelajaran, 'kelompokMapels' => $kelompokMapels]);
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:mata_pelajarans,nama,' . $mataPelajaran->id,
            'kelompok_mapel_id' => 'nullable|exists:kelompok_mapels,id',
        ]);
        $mataPelajaran->update($validated);
        return redirect()->route('mata-pelajarans.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();
        return redirect()->route('mata-pelajarans.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
