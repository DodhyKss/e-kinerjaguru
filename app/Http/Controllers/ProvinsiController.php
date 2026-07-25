<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Provinsi::query();
        
        // Add specific eager loads or orders
        if ('Provinsi' === 'MataPelajaran') {
            $query->with('kelompokMapel')->latest();
        } elseif ('Provinsi' === 'Kabupaten') {
            $query->with('provinsi')->latest();
        } elseif ('Provinsi' === 'KelompokMapel') {
            $query->orderBy('nama_kelompok_mapel');
        } else {
            $query->latest();
        }

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $provinsis = $query->paginate(10)->withQueryString();
        $allData = \App\Models\Provinsi::orderBy('nama')->get(['id', 'nama']);

        return view('provinsis.index', compact('provinsis', 'allData'));
    }

    public function create()
    {
        return view('provinsis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:provinsis,nama',
        ]);

        Provinsi::create($request->all());

        return redirect()->route('provinsis.index')->with('success', 'Provinsi berhasil ditambahkan.');
    }

    public function edit(Provinsi $provinsi)
    {
        return view('provinsis.edit', compact('provinsi'));
    }

    public function update(Request $request, Provinsi $provinsi)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:provinsis,nama,' . $provinsi->id,
        ]);

        $provinsi->update($request->all());

        return redirect()->route('provinsis.index')->with('success', 'Provinsi berhasil diperbarui.');
    }

    public function destroy(Provinsi $provinsi)
    {
        $provinsi->delete();

        return redirect()->route('provinsis.index')->with('success', 'Provinsi berhasil dihapus.');
    }
}
