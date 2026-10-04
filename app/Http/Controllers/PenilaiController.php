<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ScopesSchoolData;
use App\Models\Guru;
use App\Models\PangkatGolongan;
use App\Models\Penilai;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenilaiController extends Controller
{
    use ScopesSchoolData;

    public function index(Request $request)
    {
        $this->authorizeSchoolManagement();

        $user = Auth::user();
        $query = Penilai::with(['school', 'gurus'])->where('jabatan', '!=', 'Kepala Sekolah')->latest();

        // Kepala sekolah & admin internal sekolah terkunci ke sekolahnya sendiri.
        $lockedSchoolId = $user->isKepalaSekolah() ? $user->school_id : $this->managedSchoolId();

        if ($lockedSchoolId !== null) {
            $query->where('school_id', $lockedSchoolId);
        } elseif ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('penilai_id')) {
            $query->where('id', $request->penilai_id);
        }

        $penilais = $query->paginate(10)->withQueryString();

        $schools = $user->isAdmin() ? School::where('status', 'aktif')->orderBy('nama')->get() : collect();
        $allPenilais = ($lockedSchoolId !== null ? Penilai::where('school_id', $lockedSchoolId) : Penilai::query())->where('jabatan', '!=', 'Kepala Sekolah')->orderBy('nama')->get();

        $gurusBelumPenilai = Guru::whereDoesntHave('user.penilai')
            ->whereDoesntHave('user.kepalaSekolah')
            ->whereNotNull('user_id')
            ->when($lockedSchoolId !== null, function ($q) use ($lockedSchoolId) {
                $q->where('school_id', $lockedSchoolId);
            })
            ->orderBy('nama')
            ->get();

        return view('penilais.index', compact('penilais', 'schools', 'allPenilais', 'gurusBelumPenilai'));
    }

    public function create()
    {
        $this->authorizeSchoolManagement();

        $schools = $this->scopedActiveSchools();
        $pangkatGolongans = PangkatGolongan::all();
        $gurus = $this->scopedAssignableGurus()->get();

        return view('penilais.create', compact('schools', 'pangkatGolongans', 'gurus'));
    }

    public function createFromGuru(Request $request)
    {
        $this->authorizeSchoolManagement();

        $guru = Guru::findOrFail($request->guru_id);
        $this->authorizeRecordSchool($guru->school_id);

        $schools = $this->scopedActiveSchools();
        $pangkatGolongans = PangkatGolongan::all();
        $gurus = $this->scopedAssignableGurus()->get();

        return view('penilais.create', compact('schools', 'pangkatGolongans', 'gurus', 'guru'));
    }

    public function store(Request $request)
    {
        $this->authorizeSchoolManagement();

        // Guru yang boleh ditugaskan harus milik sekolah yang dikelola.
        $schoolIdForGuru = $this->managedSchoolId() ?? $request->input('school_id');

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100',
            'instansi' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'guru_ids' => 'nullable|array',
            'guru_ids.*' => [Rule::exists('gurus', 'id')->where(fn ($q) => $q->where('school_id', $schoolIdForGuru))],
        ]);

        // Admin internal sekolah tidak boleh menentukan sekolahnya sendiri.
        $validated['school_id'] = $this->resolveSchoolId($validated);

        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser && $existingUser->penilai) {
            return back()->withErrors(['email' => 'Email ini sudah terdaftar sebagai Asesor.'])->withInput();
        }

        DB::transaction(function () use ($validated, $existingUser) {
            if ($existingUser) {
                $user = $existingUser;
            } else {
                // Default password for Penilai is "password123" if NIP is empty, otherwise NIP
                $password = ! empty($validated['nip']) ? $validated['nip'] : 'password123';

                $user = User::create([
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'password' => Hash::make($password),
                    'role' => 'penilai',
                    'school_id' => $validated['school_id'],
                ]);
            }

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

            if (! empty($validated['guru_ids'])) {
                $penilai->gurus()->sync($validated['guru_ids']);
            }
        });

        return redirect()->route('penilais.index')->with('success', 'Data Asesor/Penilai berhasil ditambahkan (Dual Profile terhubung jika menggunakan email Guru).');
    }

    public function edit(Penilai $penilai)
    {
        $this->authorizeSchoolManagement();
        $this->authorizeRecordSchool($penilai->school_id);

        $schools = $this->scopedActiveSchools();
        $pangkatGolongans = PangkatGolongan::all();
        $gurus = $this->scopedAssignableGurus()
            ->where('user_id', '!=', $penilai->user_id)
            ->get();
        $assignedGuruIds = $penilai->gurus()->pluck('gurus.id')->toArray();

        return view('penilais.edit', compact('penilai', 'schools', 'pangkatGolongans', 'gurus', 'assignedGuruIds'));
    }

    public function update(Request $request, Penilai $penilai)
    {
        $this->authorizeSchoolManagement();
        $this->authorizeRecordSchool($penilai->school_id);

        // Guru yang boleh ditugaskan harus milik sekolah yang dikelola.
        $schoolIdForGuru = $this->managedSchoolId() ?? $penilai->school_id;

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100',
            'instansi' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,'.$penilai->user_id,
            'guru_ids' => 'nullable|array',
            'guru_ids.*' => [Rule::exists('gurus', 'id')->where(fn ($q) => $q->where('school_id', $schoolIdForGuru))],
        ]);

        // Admin internal sekolah tidak boleh memindahkan asesor antar sekolah.
        $validated['school_id'] = $this->resolveSchoolId($validated);

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

            $guruIdsToSync = [];
            if (! empty($validated['guru_ids'])) {
                $guruIdsToSync = Guru::whereIn('id', $validated['guru_ids'])
                    ->where('user_id', '!=', $penilai->user_id)
                    ->pluck('id')->toArray();
            }
            $penilai->gurus()->sync($guruIdsToSync);
        });

        return redirect()->route('penilais.index')->with('success', 'Data Asesor/Penilai berhasil diperbarui.');
    }

    public function destroy(Penilai $penilai)
    {
        $this->authorizeSchoolManagement();
        $this->authorizeRecordSchool($penilai->school_id);

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

    /**
     * Sekolah aktif yang boleh dipilih; admin internal hanya melihat sekolahnya.
     */
    private function scopedActiveSchools()
    {
        // Kolom yang dipakai adalah 'id' karena query ini dijalankan pada tabel schools.
        return $this->applySchoolScope(School::where('status', 'aktif'), 'id')->orderBy('nama')->get();
    }

    /**
     * Guru aktif yang boleh ditugaskan ke asesor; dibatasi ke sekolah yang dikelola.
     */
    private function scopedAssignableGurus()
    {
        return $this->applySchoolScope(
            Guru::with('school')->where('status', 'aktif')
        );
    }
}
