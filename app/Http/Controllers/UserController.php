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
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderBy('role')->latest()->paginate(15)->withQueryString();

        $allUsers = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('users.index', compact('users', 'allUsers'));
    }

    public function resetPassword(User $user)
    {
        // Reset password ke default: 12345678
        $user->update([
            'password' => Hash::make('12345678')
        ]);

        return back()->with('success', "Password untuk pengguna {$user->name} ({$user->email}) berhasil direset menjadi: 12345678");
    }
}
