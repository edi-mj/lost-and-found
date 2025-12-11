<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/report/lost', [ReportController::class, 'createLost'])->name('report.lost');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/report/found', [ReportController::class, 'createFound'])->name('report.found');
Route::get('/reports/lost-list', [ReportController::class, 'indexLost'])->name('reports.lost.index');

Route::get('/found-items', [App\Http\Controllers\ReportController::class, 'indexFound'])->name('report.found-list');

Route::get('/search', [SearchController::class, 'index'])->name('search.index'); 