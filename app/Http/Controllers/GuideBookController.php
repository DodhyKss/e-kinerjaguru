<?php

namespace App\Http\Controllers;

use App\Models\GuideBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GuideBookController extends Controller
{
    /**
     * Display a listing of the resource (Admin only).
     */
    public function index()
    {
        $guideBooks = GuideBook::orderBy('created_at', 'desc')->get();
        return view('guide_books.index', compact('guideBooks'));
    }

    /**
     * Show the form for creating a new resource (Admin only).
     */
    public function create()
    {
        return view('guide_books.create');
    }

    /**
     * Store a newly created resource in storage (Admin only).
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->has('is_active') ? true : false;

        if ($isActive) {
            // Set all other guide books to inactive
            GuideBook::query()->update(['is_active' => false]);
        }

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('guide_books'), $filename);
            
            $path = 'guide_books/' . $filename;
            $originalName = $file->getClientOriginalName();

            // If this is the first guide book uploaded, force it to be active
            if (GuideBook::count() === 0) {
                $isActive = true;
            }

            GuideBook::create([
                'judul' => $request->judul,
                'file_path' => $path,
                'original_filename' => $originalName,
                'is_active' => $isActive,
            ]);

            return redirect()->route('guide-books.index')->with('success', 'Buku panduan PDF berhasil diunggah.');
        }

        return back()->with('error', 'Gagal mengunggah file PDF.');
    }

    /**
     * Toggle the active status of the guide book (Admin only).
     */
    public function toggleActive(GuideBook $guideBook)
    {
        // Set all to inactive
        GuideBook::query()->update(['is_active' => false]);
        
        // Set selected to active
        $guideBook->update(['is_active' => true]);

        return redirect()->route('guide-books.index')->with('success', 'Status buku panduan aktif berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (Admin only).
     */
    public function destroy(GuideBook $guideBook)
    {
        try {
            $filePath = public_path($guideBook->file_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $wasActive = $guideBook->is_active;
            $guideBook->delete();

            // If the deleted one was active, set the latest remaining one as active
            if ($wasActive) {
                $latest = GuideBook::latest()->first();
                if ($latest) {
                    $latest->update(['is_active' => true]);
                }
            }

            return redirect()->route('guide-books.index')->with('success', 'Buku panduan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('guide-books.index')->with('error', 'Gagal menghapus buku panduan.');
        }
    }

    /**
     * Download the specified guide book (Admin only).
     */
    public function download(GuideBook $guideBook)
    {
        $filePath = public_path($guideBook->file_path);
        if (!File::exists($filePath)) {
            return back()->with('error', 'File PDF fisik tidak ditemukan di server.');
        }

        return response()->download($filePath, $guideBook->original_filename);
    }

    /**
     * Public route to download the active guide book.
     */
    public function downloadActive()
    {
        $guideBook = GuideBook::where('is_active', true)->latest()->first();

        if (!$guideBook) {
            // Fallback: get the latest uploaded if none is marked active
            $guideBook = GuideBook::latest()->first();
        }

        if (!$guideBook) {
            return back()->with('error', 'Buku panduan PDF belum tersedia saat ini. Silakan hubungi admin sekolah.');
        }

        $filePath = public_path($guideBook->file_path);
        if (!File::exists($filePath)) {
            return back()->with('error', 'File PDF buku panduan tidak ditemukan di server. Silakan hubungi admin sekolah.');
        }

        return response()->download($filePath, $guideBook->original_filename);
    }
}
