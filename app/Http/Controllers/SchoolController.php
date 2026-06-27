<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = School::with(['provinsi', 'kabupaten'])->latest();

        if ($request->filled('kabupaten_id')) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        if ($request->filled('school_id')) {
            $query->where('id', $request->school_id);
        }

        $schools = $query->paginate(10)->withQueryString();
        
        $kabupatens = Kabupaten::orderBy('nama')->get();
        $allSchools = School::orderBy('nama')->get();

        return view('schools.index', compact('schools', 'kabupatens', 'allSchools'));
    }

    public function create()
    {
        $provinsis = Provinsi::all();
        $kepsekUsers = \App\Models\User::where('role', 'kepala_sekolah')->get();
        return view('schools.create', compact('provinsis', 'kepsekUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'npsn' => 'required|string|unique:schools,npsn|max:20',
            'alamat' => 'required|string',
            'kabupaten_id' => 'required|exists:kabupatens,id',
            'provinsi_id' => 'required|exists:provinsis,id',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'kepala_sekolah' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        School::create($validated);

        return redirect()->route('schools.index')->with('success', 'Data Sekolah berhasil ditambahkan.');
    }

    public function edit(School $school)
    {
        $provinsis = Provinsi::all();
        // Get kabupatens for selected provinsi
        $kabupatens = $school->provinsi_id ? Kabupaten::where('provinsi_id', $school->provinsi_id)->get() : [];
        $kepsekUsers = \App\Models\User::where('role', 'kepala_sekolah')->get();
        
        return view('schools.edit', compact('school', 'provinsis', 'kabupatens', 'kepsekUsers'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'npsn' => 'required|string|max:20|unique:schools,npsn,' . $school->id,
            'alamat' => 'required|string',
            'kabupaten_id' => 'required|exists:kabupatens,id',
            'provinsi_id' => 'required|exists:provinsis,id',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'kepala_sekolah' => 'nullable|string|max:100',
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
