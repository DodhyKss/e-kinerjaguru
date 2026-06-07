<?php

namespace App\Http\Controllers;

use App\Models\KompetensiKeahlian;
use Illuminate\Http\Request;

class KompetensiKeahlianController extends Controller
{
    public function index()
    {
        $data = KompetensiKeahlian::latest()->paginate(10);
        return view('kompetensi-keahlians.index', compact('data'));
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
