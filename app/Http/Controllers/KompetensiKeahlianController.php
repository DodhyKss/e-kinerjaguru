<?php

namespace App\Http\Controllers;

use App\Models\KompetensiKeahlian;
use Illuminate\Http\Request;

class KompetensiKeahlianController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\KompetensiKeahlian::query();
        
        // Add specific eager loads or orders
        if ('KompetensiKeahlian' === 'MataPelajaran') {
            $query->with('kelompokMapel')->latest();
        } elseif ('KompetensiKeahlian' === 'Kabupaten') {
            $query->with('provinsi')->latest();
        } elseif ('KompetensiKeahlian' === 'KelompokMapel') {
            $query->orderBy('nama_kelompok_mapel');
        } else {
            $query->latest();
        }

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $data = $query->paginate(10)->withQueryString();
        $allData = \App\Models\KompetensiKeahlian::orderBy('nama')->get(['id', 'nama']);

        return view('kompetensi-keahlians.index', compact('data', 'allData'));
    }

    public function create()
    {
        return view('kompetensi-keahlians.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:kompetensi_keahlians,nama']);
        KompetensiKeahlian::create($request->all());
        return redirect()->route('kompetensi-keahlians.index')->with('success', 'Kompetensi Keahlian berhasil ditambahkan.');
    }

    public function edit(KompetensiKeahlian $kompetensiKeahlian)
    {
        return view('kompetensi-keahlians.edit', ['model' => $kompetensiKeahlian]);
    }

    public function update(Request $request, KompetensiKeahlian $kompetensiKeahlian)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:kompetensi_keahlians,nama,' . $kompetensiKeahlian->id]);
        $kompetensiKeahlian->update($request->all());
        return redirect()->route('kompetensi-keahlians.index')->with('success', 'Kompetensi Keahlian berhasil diperbarui.');
    }

    public function destroy(KompetensiKeahlian $kompetensiKeahlian)
    {
        $kompetensiKeahlian->delete();
        return redirect()->route('kompetensi-keahlians.index')->with('success', 'Kompetensi Keahlian berhasil dihapus.');
    }
}
