<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $data = MataPelajaran::latest()->paginate(10);
        return view('mata-pelajarans.index', compact('data'));
    }

    public function create()
    {
        return view('mata-pelajarans.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:mata_pelajarans,nama']);
        MataPelajaran::create($request->all());
        return redirect()->route('mata-pelajarans.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('mata-pelajarans.edit', ['model' => $mataPelajaran]);
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:mata_pelajarans,nama,' . $mataPelajaran->id]);
        $mataPelajaran->update($request->all());
        return redirect()->route('mata-pelajarans.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();
        return redirect()->route('mata-pelajarans.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
