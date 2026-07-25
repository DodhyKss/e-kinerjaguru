<?php

namespace App\Http\Controllers;

use App\Models\JabatanFungsional;
use Illuminate\Http\Request;

class JabatanFungsionalController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\JabatanFungsional::query();
        
        // Add specific eager loads or orders
        if ('JabatanFungsional' === 'MataPelajaran') {
            $query->with('kelompokMapel')->latest();
        } elseif ('JabatanFungsional' === 'Kabupaten') {
            $query->with('provinsi')->latest();
        } elseif ('JabatanFungsional' === 'KelompokMapel') {
            $query->orderBy('nama_kelompok_mapel');
        } else {
            $query->latest();
        }

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $data = $query->paginate(10)->withQueryString();
        $allData = \App\Models\JabatanFungsional::orderBy('nama')->get(['id', 'nama']);

        return view('jabatan-fungsionals.index', compact('data', 'allData'));
    }

    public function create()
    {
        return view('jabatan-fungsionals.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jabatan_fungsionals,nama']);
        JabatanFungsional::create($request->all());
        return redirect()->route('jabatan-fungsionals.index')->with('success', 'Jabatan Fungsional berhasil ditambahkan.');
    }

    public function edit(JabatanFungsional $jabatanFungsional)
    {
        return view('jabatan-fungsionals.edit', ['model' => $jabatanFungsional]);
    }

    public function update(Request $request, JabatanFungsional $jabatanFungsional)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jabatan_fungsionals,nama,' . $jabatanFungsional->id]);
        $jabatanFungsional->update($request->all());
        return redirect()->route('jabatan-fungsionals.index')->with('success', 'Jabatan Fungsional berhasil diperbarui.');
    }

    public function destroy(JabatanFungsional $jabatanFungsional)
    {
        $jabatanFungsional->delete();
        return redirect()->route('jabatan-fungsionals.index')->with('success', 'Jabatan Fungsional berhasil dihapus.');
    }
}
