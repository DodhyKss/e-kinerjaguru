<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::latest()->paginate(10);
        return view('schools.index', compact('schools'));
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'npsn' => 'required|string|unique:schools,npsn|max:20',
            'alamat' => 'required|string',
            'kabupaten' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'kepala_sekolah' => 'required|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        School::create($validated);

        return redirect()->route('schools.index')->with('success', 'Data Sekolah berhasil ditambahkan.');
    }

    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'npsn' => 'required|string|max:20|unique:schools,npsn,' . $school->id,
            'alamat' => 'required|string',
            'kabupaten' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'kepala_sekolah' => 'required|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $school->update($validated);

        return redirect()->route('schools.index')->with('success', 'Data Sekolah berhasil diperbarui.');
    }

    public function destroy(School $school)
    {
        try {
            $school->delete();
            return redirect()->route('schools.index')->with('success', 'Data Sekolah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('schools.index')->with('error', 'Gagal menghapus Sekolah karena memiliki relasi data (Guru/Penilai).');
        }
    }
}
