<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    public function index()
    {
        $kabupatens = Kabupaten::with('provinsi')->latest()->paginate(10);
        return view('kabupatens.index', compact('kabupatens'));
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
