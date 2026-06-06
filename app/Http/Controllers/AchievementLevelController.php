<?php

namespace App\Http\Controllers;

use App\Models\AchievementLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementLevelController extends Controller
{
    public function update(Request $request, AchievementLevel $level)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'deskripsi' => 'required|string',
        ]);

        $level->update($validated);

        return back()->with('success', "Deskripsi Level Capaian {$level->level} berhasil diperbarui.");
    }
}
