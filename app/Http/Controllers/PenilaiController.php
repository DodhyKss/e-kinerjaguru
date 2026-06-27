<?php

namespace App\Http\Controllers;

use App\Models\Penilai;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenilaiController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isKepalaSekolah()) {
            abort(403);
        }
        
        $query = Penilai::with(['school', 'gurus'])->latest();
        
        if (Auth::user()->isKepalaSekolah()) {
            $query->where('school_id', Auth::user()->school_id);
        }

        $penilais = $query->paginate(10);
        return view('penilais.index', compact('penilais'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $schools = School::where('status', 'aktif')->get();
        $pangkatGolongans = \App\Models\PangkatGolongan::all();
        $gurus = \App\Models\Guru::with('school')->where('status', 'aktif')->get();
        return view('penilais.create', compact('schools', 'pangkatGolongans', 'gurus'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100',
            'instansi' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email|max:255',
            'guru_ids' => 'nullable|array',
            'guru_ids.*' => 'exists:gurus,id',
        ]);

        DB::transaction(function () use ($validated) {
            // Default password for Penilai is "password123" if NIP is empty, otherwise NIP
            $password = !empty($validated['nip']) ? $validated['nip'] : 'password123';

            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'penilai',
                'school_id' => $validated['school_id'],
            ]);

            $penilai = Penilai::create([
                'user_id' => $user->id,
                'school_id' => $validated['school_id'],
                'pangkat_golongan_id' => $validated['pangkat_golongan_id'] ?? null,
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'jabatan' => $validated['jabatan'],
                'instansi' => $validated['instansi'],
                'no_telepon' => $validated['no_telepon'],
            ]);

            if (!empty($validated['guru_ids'])) {
                $penilai->gurus()->sync($validated['guru_ids']);
            }
        });

        return redirect()->route('penilais.index')->with('success', 'Data Asesor/Penilai berhasil ditambahkan dan Akun otomatis dibuat.');
    }

    public function edit(Penilai $penilai)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $schools = School::where('status', 'aktif')->get();
        $pangkatGolongans = \App\Models\PangkatGolongan::all();
        $gurus = \App\Models\Guru::with('school')->where('status', 'aktif')->get();
        $assignedGuruIds = $penilai->gurus()->pluck('gurus.id')->toArray();
        return view('penilais.edit', compact('penilai', 'schools', 'pangkatGolongans', 'gurus', 'assignedGuruIds'));
    }

    public function update(Request $request, Penilai $penilai)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100',
            'instansi' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $penilai->user_id,
            'guru_ids' => 'nullable|array',
            'guru_ids.*' => 'exists:gurus,id',
        ]);

        DB::transaction(function () use ($validated, $penilai) {
            $penilai->user->update([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'school_id' => $validated['school_id'],
            ]);

            $penilai->update([
                'school_id' => $validated['school_id'],
                'pangkat_golongan_id' => $validated['pangkat_golongan_id'] ?? null,
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'jabatan' => $validated['jabatan'],
                'instansi' => $validated['instansi'],
                'no_telepon' => $validated['no_telepon'],
            ]);

            $penilai->gurus()->sync($validated['guru_ids'] ?? []);
        });

        return redirect()->route('penilais.index')->with('success', 'Data Asesor/Penilai berhasil diperbarui.');
    }

    public function destroy(Penilai $penilai)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($penilai) {
                $user = $penilai->user;
                $penilai->delete();
                if ($user) {
                    $user->delete();
                }
            });
            return redirect()->route('penilais.index')->with('success', 'Data Asesor/Penilai dan Akunnya berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('penilais.index')->with('error', 'Gagal menghapus Asesor karena sedang menangani evaluasi guru.');
        }
    }
}
