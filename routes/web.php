<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Route Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route Register
Route::get('/register', [RegisterController::class, 'showRegister'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route School Dashboard
    Route::get('/school-dashboard', [GalleryController::class, 'index'])->name('school.dashboard');
    
    // Route untuk admin saja
    Route::middleware('role:admin')->group(function () {
        // Route Absensi
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/{kelas}', [AbsensiController::class, 'show'])->name('absensi.show');
        
        // Route Import Absensi (di dalam halaman input)
        Route::post('/absensi/import', [AbsensiController::class, 'import'])->name('absensi.import');
        Route::get('/absensi/template', [AbsensiController::class, 'downloadTemplate'])->name('absensi.template');
        
        // Route User Management
        Route::get('/users', [RegisterController::class, 'index'])->name('users.index');
        Route::delete('/users/{id}', [RegisterController::class, 'delete'])->name('users.delete');
        Route::put('/users/{id}/role', [RegisterController::class, 'editRole'])->name('users.role');
        Route::post('/users/{id}/reset-password', [RegisterController::class, 'resetPassword'])->name('users.reset-password');
        
        // Route Gallery Management
        Route::get('/gallery/manage', [GalleryController::class, 'manage'])->name('gallery.manage');
        Route::get('/gallery/{id}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
        Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
        Route::put('/gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::patch('/gallery/{id}/toggle', [GalleryController::class, 'toggle'])->name('gallery.toggle');
    });
});