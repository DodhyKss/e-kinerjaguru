<?php

namespace App\Http\Controllers;

use App\Models\PangkatGolongan;
use Illuminate\Http\Request;

class PangkatGolonganController extends Controller
{
    public function index()
    {
        $data = PangkatGolongan::latest()->paginate(10);
        return view('pangkat-golongans.index', compact('data'));
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
