<?php

namespace App\Http\Controllers;

use App\Models\AchievementLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementLevelController extends Controller
{
    public function update(Request $request, $indicator, AchievementLevel $level)
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

    public function bulkUpdate(Request $request, $indicator)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'levels' => 'required|array',
            'levels.*.deskripsi' => 'required|string',
        ]);

        foreach ($validated['levels'] as $levelId => $data) {
            AchievementLevel::where('id', $levelId)->update(['deskripsi' => $data['deskripsi']]);
        }

        return back()->with('success', "Semua Deskripsi Level Capaian berhasil diperbarui.");
    }
}
