<?php

namespace App\Http\Controllers;

use App\Models\PangkatGolongan;
use Illuminate\Http\Request;

class PangkatGolonganController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\PangkatGolongan::query();
        
        // Add specific eager loads or orders
        if ('PangkatGolongan' === 'MataPelajaran') {
            $query->with('kelompokMapel')->latest();
        } elseif ('PangkatGolongan' === 'Kabupaten') {
            $query->with('provinsi')->latest();
        } elseif ('PangkatGolongan' === 'KelompokMapel') {
            $query->orderBy('nama_kelompok_mapel');
        } else {
            $query->latest();
        }

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $data = $query->paginate(10)->withQueryString();
        $allData = \App\Models\PangkatGolongan::orderBy('nama')->get(['id', 'nama']);

        return view('pangkat-golongans.index', compact('data', 'allData'));
    }

    public function create()
    {
        return view('pangkat-golongans.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:pangkat_golongans,nama']);
        PangkatGolongan::create($request->all());
        return redirect()->route('pangkat-golongans.index')->with('success', 'Pangkat / Golongan berhasil ditambahkan.');
    }

    public function edit(PangkatGolongan $pangkatGolongan)
    {
        return view('pangkat-golongans.edit', ['model' => $pangkatGolongan]);
    }

    public function update(Request $request, PangkatGolongan $pangkatGolongan)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:pangkat_golongans,nama,' . $pangkatGolongan->id]);
        $pangkatGolongan->update($request->all());
        return redirect()->route('pangkat-golongans.index')->with('success', 'Pangkat / Golongan berhasil diperbarui.');
    }

    public function destroy(PangkatGolongan $pangkatGolongan)
    {
        $pangkatGolongan->delete();
        return redirect()->route('pangkat-golongans.index')->with('success', 'Pangkat / Golongan berhasil dihapus.');
    }
}
