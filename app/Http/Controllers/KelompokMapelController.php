<?php

namespace App\Http\Controllers;

use App\Models\KelompokMapel;
use Illuminate\Http\Request;

class KelompokMapelController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\KelompokMapel::query();
        
        // Add specific eager loads or orders
        if ('KelompokMapel' === 'MataPelajaran') {
            $query->with('kelompokMapel')->latest();
        } elseif ('KelompokMapel' === 'Kabupaten') {
            $query->with('provinsi')->latest();
        } elseif ('KelompokMapel' === 'KelompokMapel') {
            $query->orderBy('nama_kelompok_mapel');
        } else {
            $query->latest();
        }

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $kelompokMapels = $query->paginate(10)->withQueryString();
        $allData = \App\Models\KelompokMapel::orderBy('nama_kelompok_mapel')->get(['id', 'nama_kelompok_mapel']);

        return view('kelompok_mapels.index', compact('kelompokMapels', 'allData'));
    }

    public function create()
    {
        return view('kelompok_mapels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelompok_mapel' => 'required|string|max:255',
        ]);

        KelompokMapel::create($validated);
        return redirect()->route('kelompok-mapels.index')->with('success', 'Kelompok Mapel berhasil ditambahkan.');
    }

    public function edit(KelompokMapel $kelompokMapel)
    {
        return view('kelompok_mapels.edit', compact('kelompokMapel'));
    }

    public function update(Request $request, KelompokMapel $kelompokMapel)
    {
        $validated = $request->validate([
            'nama_kelompok_mapel' => 'required|string|max:255',
        ]);

        $kelompokMapel->update($validated);
        return redirect()->route('kelompok-mapels.index')->with('success', 'Kelompok Mapel berhasil diperbarui.');
    }

    public function destroy(KelompokMapel $kelompokMapel)
    {
        try {
            $kelompokMapel->delete();
            return redirect()->route('kelompok-mapels.index')->with('success', 'Kelompok Mapel berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kelompok-mapels.index')->with('error', 'Gagal menghapus Kelompok Mapel karena data terkait masih ada.');
        }
    }
}
