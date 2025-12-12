<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\VerificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard-admin');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::post('/verifications', [VerificationController::class, 'create'])->name('verifications.create');
Route::get('/verifications/{reportId}', [VerificationController::class, 'status'])->name('verifications.status');
Route::put('/verifications/{id}', [VerificationController::class, 'update'])->middleware('admin')->name('verifications.update');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::prefix('reports')->group(function () {
    // Lihat semua lost
    Route::get('/lost', [ReportController::class, 'indexLost'])->name('reports.lost.index');
    // Lihat semua found
    Route::get('/found', [ReportController::class, 'indexFound'])->name('reports.found.index');
    // Form tambah lost
    Route::get('/lost/create', [ReportController::class, 'createLost'])->name('reports.lost.create');
    // Form tambah found
    Route::get('/found/create', [ReportController::class, 'createFound'])->name('reports.found.create');
    // Simpan lost
    Route::post('/lost', [ReportController::class, 'store'])->name('reports.lost.store')->defaults('type', 'lost');
    // Simpan found
    Route::post('/found', [ReportController::class, 'store'])->name('reports.found.store')->defaults('type', 'found');
    // Detail laporan
    Route::get('/{id}', [ReportController::class, 'show'])->name('reports.show');
});
