<?php

namespace App\Http\Controllers;

use App\Models\KelompokMapel;
use Illuminate\Http\Request;

class KelompokMapelController extends Controller
{
    public function index()
    {
        $kelompokMapels = KelompokMapel::orderBy('nama_kelompok_mapel')->get();
        return view('kelompok_mapels.index', compact('kelompokMapels'));
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
