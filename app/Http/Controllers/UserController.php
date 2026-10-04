<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = User::with(['school', 'guru', 'penilai', 'kepalaSekolah']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Admin internal sekolah hanya boleh melihat akun sekolahnya sendiri.
        if ($user->isAdminInternal()) {
            $query->where('school_id', $user->school_id);
        } elseif ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $users = $query->orderBy('role')->latest()->paginate(15)->withQueryString();

        if ($user->isAdminInternal()) {
            $allUsers = collect();
            $schools = collect();
        } else {
            $allUsers = User::orderBy('name')->get(['id', 'name', 'email']);
            $schools = School::orderBy('nama')->get(['id', 'nama']);
        }

        return view('users.index', compact('users', 'allUsers', 'schools'));
    }

    /**
     * Membuat akun Admin Internal Sekolah (khusus Admin Pusat).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'school_id.required' => 'Sekolah wajib dipilih.',
            'school_id.exists' => 'Sekolah yang dipilih tidak ditemukan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user = User::create([
            'name' => $validator->validated()['name'],
            'email' => $validator->validated()['email'],
            'password' => Hash::make($validator->validated()['password']),
            'role' => 'admin_internal',
            'school_id' => $validator->validated()['school_id'],
            'is_active' => true,
        ]);

        return back()->with('success', "Akun Admin Internal Sekolah untuk {$user->name} ({$user->email}) berhasil dibuat.");
    }

    public function resetPassword(Request $request, User $user)
    {
        if ($error = $this->authorizeTarget($user, 'mereset password')) {
            return back()->with('error', $error);
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', "Password untuk pengguna {$user->name} ({$user->email}) berhasil direset sesuai password baru yang Anda tentukan.");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        if ($error = $this->authorizeTarget($user, 'mengubah status aktif')) {
            return back()->with('error', $error);
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        // Sinkronkan status penilai (termasuk profil ganda Kepala Sekolah)
        if ($user->penilai) {
            $user->penilai->update(['status' => $user->is_active ? 'aktif' : 'nonaktif']);
        }

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun pengguna {$user->name} ({$user->email}) berhasil {$status}. ".($user->is_active ? '' : 'Pengguna tersebut tidak dapat login sampai akunnya diaktifkan kembali.'));
    }

    /**
     * Admin internal sekolah hanya boleh menyentuh akun non-privileged
     * yang berada di sekolahnya sendiri.
     *
     * @return string|null Pesan error, atau null bila akses diizinkan.
     */
    private function authorizeTarget(User $target, string $action): ?string
    {
        $actor = auth()->user();

        if ($actor->isAdmin()) {
            return null;
        }

        if (! $actor->isAdminInternal()) {
            abort(403, 'Unauthorized action.');
        }

        if ($target->isManageableBy($actor)) {
            return null;
        }

        if ($target->isPrivilegedRole()) {
            return "Anda tidak diperbolehkan {$action} akun admin (pusat atau internal sekolah).";
        }

        return "Anda hanya dapat {$action} akun milik sekolah Anda sendiri.";
    }
}
