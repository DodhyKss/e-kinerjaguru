<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MataPelajaran;
use App\Models\KompetensiKeahlian;
use App\Models\PangkatGolongan;
use App\Models\JabatanFungsional;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Guru::with(['school', 'mataPelajaran'])->latest();

        if ($user->isKepalaSekolah()) {
            $query->where('school_id', $user->school_id);
        } elseif ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }

        if ($request->filled('guru_id')) {
            $query->where('id', $request->guru_id);
        }

        $gurus = $query->paginate(10)->withQueryString();
        
        $schools = $user->isAdmin() ? School::where('status', 'aktif')->orderBy('nama')->get() : collect();
        $mataPelajarans = MataPelajaran::orderBy('nama')->get();
        $allGurus = ($user->isKepalaSekolah() ? Guru::where('school_id', $user->school_id) : Guru::query())->orderBy('nama')->get();

        $penilaisBelumGuru = \App\Models\Penilai::whereDoesntHave('user.guru')
            ->whereNotNull('user_id')
            ->whereHas('user', function($q) {
                $q->where('role', '!=', 'kepala_sekolah');
            })
            ->when($user->isKepalaSekolah(), function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })
            ->orderBy('nama')
            ->get();

        return view('gurus.index', compact('gurus', 'schools', 'mataPelajarans', 'allGurus', 'penilaisBelumGuru'));
    }

    public function create()
    {
        // Hanya Admin yang bisa menambah guru lintas sekolah
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $schools = School::where('status', 'aktif')->get();
        $mataPelajarans = MataPelajaran::with('kelompokMapel')->orderBy('nama')->get();
        $kompetensiKeahlians = KompetensiKeahlian::orderBy('nama')->get();
        $pangkatGolongans = PangkatGolongan::orderBy('nama')->get();
        $jabatanFungsionals = JabatanFungsional::orderBy('nama')->get();
        
        return view('gurus.create', compact('schools', 'mataPelajarans', 'kompetensiKeahlians', 'pangkatGolongans', 'jabatanFungsionals'));
    }

    public function createFromPenilai(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $penilai = \App\Models\Penilai::findOrFail($request->penilai_id);

        $schools = School::where('status', 'aktif')->get();
        $mataPelajarans = MataPelajaran::with('kelompokMapel')->orderBy('nama')->get();
        $kompetensiKeahlians = KompetensiKeahlian::orderBy('nama')->get();
        $pangkatGolongans = PangkatGolongan::orderBy('nama')->get();
        $jabatanFungsionals = JabatanFungsional::orderBy('nama')->get();
        
        return view('gurus.create', compact('schools', 'mataPelajarans', 'kompetensiKeahlians', 'pangkatGolongans', 'jabatanFungsionals', 'penilai'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip|max:50',
            'nuptk' => 'nullable|string|max:50',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kompetensi_keahlian_id' => 'nullable|exists:kompetensi_keahlians,id',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'jabatan_fungsional_id' => 'nullable|exists:jabatan_fungsionals,id',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser && $existingUser->guru) {
            return back()->withErrors(['email' => 'Email ini sudah terdaftar sebagai Guru.'])->withInput();
        }

        DB::transaction(function () use ($validated, $existingUser) {
            if ($existingUser) {
                // Gunakan user yang sudah ada (misal dari data Penilai)
                $user = $existingUser;
            } else {
                // 1. Create User account for the Guru
                $user = User::create([
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['nip']), // Default password = NIP
                    'role' => 'guru',
                    'school_id' => $validated['school_id'],
                ]);
            }

            // 2. Create Guru profile
            Guru::create([
                'user_id' => $user->id,
                'school_id' => $validated['school_id'],
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'nuptk' => $validated['nuptk'],
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'kompetensi_keahlian_id' => $validated['kompetensi_keahlian_id'] ?? null,
                'pangkat_golongan_id' => $validated['pangkat_golongan_id'] ?? null,
                'jabatan_fungsional_id' => $validated['jabatan_fungsional_id'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
            ]);
        });

        return redirect()->route('gurus.index')->with('success', 'Data Guru berhasil ditambahkan (Dual Profile terhubung jika menggunakan email Asesor).');
    }

    public function edit(Guru $guru)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $schools = School::where('status', 'aktif')->get();
        $mataPelajarans = MataPelajaran::with('kelompokMapel')->orderBy('nama')->get();
        $kompetensiKeahlians = KompetensiKeahlian::orderBy('nama')->get();
        $pangkatGolongans = PangkatGolongan::orderBy('nama')->get();
        $jabatanFungsionals = JabatanFungsional::orderBy('nama')->get();

        return view('gurus.edit', compact('guru', 'schools', 'mataPelajarans', 'kompetensiKeahlians', 'pangkatGolongans', 'jabatanFungsionals'));
    }

    public function update(Request $request, Guru $guru)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:gurus,nip,' . $guru->id,
            'nuptk' => 'nullable|string|max:50',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kompetensi_keahlian_id' => 'nullable|exists:kompetensi_keahlians,id',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'jabatan_fungsional_id' => 'nullable|exists:jabatan_fungsionals,id',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $guru->user_id,
        ]);

        DB::transaction(function () use ($validated, $guru) {
            // Update User
            $guru->user->update([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'school_id' => $validated['school_id'],
            ]);

            // Update Guru
            $guru->update([
                'school_id' => $validated['school_id'],
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'nuptk' => $validated['nuptk'],
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'kompetensi_keahlian_id' => $validated['kompetensi_keahlian_id'] ?? null,
                'pangkat_golongan_id' => $validated['pangkat_golongan_id'] ?? null,
                'jabatan_fungsional_id' => $validated['jabatan_fungsional_id'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
            ]);
        });

        return redirect()->route('gurus.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($guru) {
                $user = $guru->user;
                $guru->delete();
                if ($user) {
                    $user->delete();
                }
            });
            return redirect()->route('gurus.index')->with('success', 'Data Guru dan Akunnya berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('gurus.index')->with('error', 'Gagal menghapus Guru karena memiliki data evaluasi yang terikat.');
        }
    }
}
