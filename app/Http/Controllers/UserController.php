<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['school', 'guru', 'penilai', 'kepalaSekolah']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $users = $query->orderBy('role')->latest()->paginate(15)->withQueryString();

        $allUsers = User::orderBy('name')->get(['id', 'name', 'email']);
        $schools = \App\Models\School::orderBy('nama')->get(['id', 'nama']);

        return view('users.index', compact('users', 'allUsers', 'schools'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', "Password untuk pengguna {$user->name} ({$user->email}) berhasil direset sesuai password baru yang Anda tentukan.");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        // Sinkronkan status penilai (termasuk profil ganda Kepala Sekolah)
        if ($user->penilai) {
            $user->penilai->update(['status' => $user->is_active ? 'aktif' : 'nonaktif']);
        }

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun pengguna {$user->name} ({$user->email}) berhasil {$status}. " . ($user->is_active ? '' : 'Pengguna tersebut tidak dapat login sampai akunnya diaktifkan kembali.'));
    }
}
