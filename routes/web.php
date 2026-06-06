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
use App\Http\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

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
        Route::resource('indicators.levels', AchievementLevelController::class)->only(['update']);
        Route::resource('indicators.aspects', AssessmentAspectController::class)->only(['store', 'update', 'destroy']);
    });

    // Master Data Guru & Penilai (Admin & Kepsek)
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::resource('gurus', GuruController::class)->except(['show']);
        Route::resource('penilais', PenilaiController::class)->except(['show']);
    });

    // Evaluations
    Route::prefix('evaluations')->name('evaluations.')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('index');
        
        // Create Penugasan (Admin & Kepsek)
        Route::middleware('role:admin,kepala_sekolah')->group(function () {
            Route::get('/create', [EvaluationController::class, 'create'])->name('create');
            Route::post('/', [EvaluationController::class, 'store'])->name('store');
            Route::post('/{evaluation}/approve', [EvaluationController::class, 'approve'])->name('approve');
        });

        Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('show');
        
        // Penilai routes
        Route::middleware('role:penilai')->group(function () {
            Route::get('/{evaluation}/indicator/{indicator}', [EvaluationController::class, 'indicatorForm'])->name('indicator');
            Route::post('/{evaluation}/indicator/{indicator}', [EvaluationController::class, 'saveIndicatorForm'])->name('indicator.save');
            Route::post('/{evaluation}/submit', [EvaluationController::class, 'submit'])->name('submit');
        });

        // Guru routes (Upload Dokumen)
        Route::middleware('role:guru')->group(function () {
            Route::get('/{evaluation}/upload/{indicator}', [EvaluationController::class, 'uploadDokumenForm'])->name('upload');
            Route::post('/{evaluation}/upload/{indicator}', [EvaluationController::class, 'storeDokumen'])->name('upload.store');
        });
    });
});
