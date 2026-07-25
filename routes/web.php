<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PenilaiController;
use App\Http\Controllers\EvaluationPeriodController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\AchievementLevelController;
use App\Http\Controllers\AssessmentAspectController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\GuideBookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/panduan', function () {
    return view('panduan');
})->name('panduan');
Route::get('/panduan/download', [GuideBookController::class, 'downloadActive'])->name('panduan.download');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data Routes (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('schools', SchoolController::class)->except(['show']);
        Route::resource('evaluation-periods', EvaluationPeriodController::class)->except(['show']);
        
        // Master Instrumen
        Route::resource('indicators', IndicatorController::class);
        Route::put('indicators/{indicator}/levels-bulk', [AchievementLevelController::class, 'bulkUpdate'])->name('indicators.levels.bulk');
        Route::resource('indicators.levels', AchievementLevelController::class)->only(['update']);
        Route::put('indicators/{indicator}/aspects-bulk', [AssessmentAspectController::class, 'bulkUpdate'])->name('indicators.aspects.bulk');
        Route::resource('indicators.aspects', AssessmentAspectController::class)->only(['store', 'update', 'destroy']);
        
        // Master Jenis Dokumen
        Route::resource('jenis-dokumens', JenisDokumenController::class);
        
        // Master Wilayah
        Route::resource('provinsis', \App\Http\Controllers\ProvinsiController::class)->except(['show']);
        Route::resource('kabupatens', \App\Http\Controllers\KabupatenController::class)->except(['show']);

        // Master Atribut Guru
        Route::resource('kelompok-mapels', \App\Http\Controllers\KelompokMapelController::class)->except(['show']);
        Route::resource('mata-pelajarans', \App\Http\Controllers\MataPelajaranController::class)->except(['show']);
        Route::resource('kompetensi-keahlians', \App\Http\Controllers\KompetensiKeahlianController::class)->except(['show']);
        Route::resource('pangkat-golongans', \App\Http\Controllers\PangkatGolonganController::class)->except(['show']);
        Route::resource('jabatan-fungsionals', \App\Http\Controllers\JabatanFungsionalController::class)->except(['show']);

        // Master Buku Panduan
        Route::resource('guide-books', GuideBookController::class)->except(['show', 'edit', 'update']);
        Route::patch('guide-books/{guide_book}/toggle-active', [GuideBookController::class, 'toggleActive'])->name('guide-books.toggle-active');
        Route::get('guide-books/{guide_book}/download', [GuideBookController::class, 'download'])->name('guide-books.download');
    });

    // API Routes for Dropdown
    Route::get('/api/kabupatens/{provinsi_id}', [\App\Http\Controllers\KabupatenController::class, 'getByProvinsi'])->name('api.kabupatens.by-provinsi');

    // Master Data Guru & Penilai & Kepsek (Admin & Kepsek)
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::resource('gurus', GuruController::class)->except(['show']);
        Route::resource('penilais', PenilaiController::class)->except(['show']);
    });
    
    // Master Kepala Sekolah (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('kepala-sekolahs', \App\Http\Controllers\KepalaSekolahController::class)->except(['show']);
    });

    // Evaluations
    Route::prefix('evaluations')->name('evaluations.')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('index');
        
        // Rekomendasi (Admin & Penilai & Kepsek) - Harus diletakkan sebelum /{evaluation} agar tidak terbaca sebagai parameter dinamis
        Route::middleware('role:admin,penilai,kepala_sekolah')->group(function () {
            Route::get('/rekomendasis', [\App\Http\Controllers\RekomendasiController::class, 'index'])->name('rekomendasis.index');
            Route::get('/{evaluation}/rekomendasi', [\App\Http\Controllers\RekomendasiController::class, 'create'])->name('rekomendasis.create');
            Route::post('/{evaluation}/rekomendasi', [\App\Http\Controllers\RekomendasiController::class, 'store'])->name('rekomendasis.store');
        });

        // Create Penugasan (Admin & Kepsek)
        Route::middleware('role:admin,kepala_sekolah')->group(function () {
            Route::get('/create', [EvaluationController::class, 'create'])->name('create');
            Route::post('/', [EvaluationController::class, 'store'])->name('store');
            Route::get('/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('edit');
            Route::put('/{evaluation}', [EvaluationController::class, 'update'])->name('update');
            Route::delete('/{evaluation}', [EvaluationController::class, 'destroy'])->name('destroy');
            Route::post('/{evaluation}/approve', [EvaluationController::class, 'approve'])->name('approve');
        });

        Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('show');
        Route::get('/{evaluation}/report', [EvaluationController::class, 'report'])->name('report');
        
        // Penilai & Kepala Sekolah routes
        Route::middleware('role:penilai,kepala_sekolah')->group(function () {
            Route::get('/{evaluation}/indicator/{indicator}', [EvaluationController::class, 'indicatorForm'])->name('indicator');
            Route::post('/{evaluation}/indicator/{indicator}', [EvaluationController::class, 'saveIndicatorForm'])->name('indicator.save');
            Route::post('/{evaluation}/submit', [EvaluationController::class, 'submit'])->name('submit');
        });

        // Guru routes (Upload Dokumen)
        Route::middleware('role:guru')->group(function () {
            Route::get('/{evaluation}/upload-general', [EvaluationController::class, 'generalUploadForm'])->name('upload-general');
            Route::post('/{evaluation}/upload-general', [EvaluationController::class, 'storeGeneralUpload'])->name('upload-general.store');
            
            Route::get('/{evaluation}/upload/{indicator}', [EvaluationController::class, 'uploadDokumenForm'])->name('upload');
            Route::post('/{evaluation}/upload/{indicator}', [EvaluationController::class, 'storeDokumen'])->name('upload.store');
        });
        
        // Show indicator details
        Route::get('/{evaluation}/indicator/{indicator}/show', [EvaluationController::class, 'showIndicator'])->name('indicator.show');
    });

    // Reports Menu
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/grafik', [\App\Http\Controllers\GraphicalReportController::class, 'index'])->name('reports.grafik');
    Route::get('/reports/ranking', [\App\Http\Controllers\RankingController::class, 'index'])->name('reports.ranking');
    Route::get('/reports/rekapitulasi', [\App\Http\Controllers\RecapController::class, 'index'])->name('reports.recap');
});
