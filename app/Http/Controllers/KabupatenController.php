<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Kabupaten::query();
        
        // Add specific eager loads or orders
        if ('Kabupaten' === 'MataPelajaran') {
            $query->with('kelompokMapel')->latest();
        } elseif ('Kabupaten' === 'Kabupaten') {
            $query->with('provinsi')->latest();
        } elseif ('Kabupaten' === 'KelompokMapel') {
            $query->orderBy('nama_kelompok_mapel');
        } else {
            $query->latest();
        }

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $kabupatens = $query->paginate(10)->withQueryString();
        $allData = \App\Models\Kabupaten::orderBy('nama')->get(['id', 'nama']);

        return view('kabupatens.index', compact('kabupatens', 'allData'));
    }

    public function create()
    {
        $provinsis = Provinsi::all();
        return view('kabupatens.create', compact('provinsis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provinsi_id' => 'required|exists:provinsis,id',
            'nama' => 'required|string|max:255',
        ]);

        Kabupaten::create($request->all());

        return redirect()->route('kabupatens.index')->with('success', 'Kabupaten berhasil ditambahkan.');
    }

    public function edit(Kabupaten $kabupaten)
    {
        $provinsis = Provinsi::all();
        return view('kabupatens.edit', compact('kabupaten', 'provinsis'));
    }

    public function update(Request $request, Kabupaten $kabupaten)
    {
        $request->validate([
            'provinsi_id' => 'required|exists:provinsis,id',
            'nama' => 'required|string|max:255',
        ]);

        $kabupaten->update($request->all());

        return redirect()->route('kabupatens.index')->with('success', 'Kabupaten berhasil diperbarui.');
    }

    public function destroy(Kabupaten $kabupaten)
    {
        $kabupaten->delete();

        return redirect()->route('kabupatens.index')->with('success', 'Kabupaten berhasil dihapus.');
    }

    // Method for API/AJAX to get Kabupatens by Provinsi ID
    public function getByProvinsi($provinsi_id)
    {
        $kabupatens = Kabupaten::where('provinsi_id', $provinsi_id)->get();
        return response()->json($kabupatens);
    }
}
