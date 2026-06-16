<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\KepalaSekolah;
use App\Models\PangkatGolongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class KepalaSekolahController extends Controller
{
    public function index()
    {
        $kepalaSekolahs = User::with(['school', 'kepalaSekolah.pangkatGolongan'])->where('role', 'kepala_sekolah')->latest()->paginate(10);
        return view('kepala_sekolahs.index', compact('kepalaSekolahs'));
    }

    public function create()
    {
        $schools = School::orderBy('nama')->get();
        $pangkatGolongans = PangkatGolongan::orderBy('nama')->get();
        return view('kepala_sekolahs.form', compact('schools', 'pangkatGolongans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'school_id' => 'nullable|exists:schools,id',
            'is_active' => 'required|boolean',
            'nip' => 'nullable|string|max:30',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'school_id' => $validated['school_id'],
                'role' => 'kepala_sekolah',
                'is_active' => $validated['is_active'],
            ]);

            KepalaSekolah::create([
                'user_id' => $user->id,
                'school_id' => $validated['school_id'],
                'nama' => $validated['name'],
                'nip' => $validated['nip'],
                'pangkat_golongan_id' => $validated['pangkat_golongan_id'],
                'no_telepon' => $validated['no_telepon'],
            ]);

            DB::commit();
            return redirect()->route('kepala-sekolahs.index')->with('success', 'Akun Kepala Sekolah berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(User $kepala_sekolah)
    {
        if ($kepala_sekolah->role !== 'kepala_sekolah') {
            abort(404);
        }
        $schools = School::orderBy('nama')->get();
        $pangkatGolongans = PangkatGolongan::orderBy('nama')->get();
        
        $kepala_sekolah->load('kepalaSekolah');
        
        return view('kepala_sekolahs.form', compact('kepala_sekolah', 'schools', 'pangkatGolongans'));
    }

    public function update(Request $request, User $kepala_sekolah)
    {
        if ($kepala_sekolah->role !== 'kepala_sekolah') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($kepala_sekolah->id)],
            'password' => 'nullable|string|min:8',
            'school_id' => 'nullable|exists:schools,id',
            'is_active' => 'required|boolean',
            'nip' => 'nullable|string|max:30',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongans,id',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'school_id' => $validated['school_id'],
                'is_active' => $validated['is_active'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $kepala_sekolah->update($data);

            $kepala_sekolah->kepalaSekolah()->updateOrCreate(
                ['user_id' => $kepala_sekolah->id],
                [
                    'school_id' => $validated['school_id'],
                    'nama' => $validated['name'],
                    'nip' => $validated['nip'],
                    'pangkat_golongan_id' => $validated['pangkat_golongan_id'],
                    'no_telepon' => $validated['no_telepon'],
                ]
            );

            DB::commit();
            return redirect()->route('kepala-sekolahs.index')->with('success', 'Akun Kepala Sekolah berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(User $kepala_sekolah)
    {
        if ($kepala_sekolah->role !== 'kepala_sekolah') {
            abort(404);
        }

        try {
            if ($kepala_sekolah->kepalaSekolah) {
                $kepala_sekolah->kepalaSekolah()->delete();
            }
            $kepala_sekolah->delete();
            return redirect()->route('kepala-sekolahs.index')->with('success', 'Akun Kepala Sekolah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kepala-sekolahs.index')->with('error', 'Gagal menghapus akun karena memiliki relasi data.');
        }
    }
}
