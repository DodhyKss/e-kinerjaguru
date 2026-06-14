<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\KelompokMapel;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $data = MataPelajaran::with('kelompokMapel')->latest()->paginate(10);
        return view('mata-pelajarans.index', compact('data'));
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
