<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    public function index()
    {
        $provinsis = Provinsi::latest()->paginate(10);
        return view('provinsis.index', compact('provinsis'));
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
