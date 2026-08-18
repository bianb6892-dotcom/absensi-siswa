<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\GuruAbsensiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\AbsensiCepatController;
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

// =============================================
// ROUTE UNTUK ADMIN (Super Admin)
// =============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // User Management (HANYA INI UNTUK ADMIN)
    Route::get('/users', [RegisterController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [RegisterController::class, 'delete'])->name('users.delete');
    Route::put('/users/{id}/role', [RegisterController::class, 'editRole'])->name('users.role');
    Route::post('/users/{id}/reset-password', [RegisterController::class, 'resetPassword'])->name('users.reset-password');
    Route::get('/users/{id}/edit', [RegisterController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [RegisterController::class, 'update'])->name('users.update');
});

// =============================================
// ROUTE UNTUK GURU
// =============================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [GuruAbsensiController::class, 'dashboard'])->name('guru.dashboard');

    // Absensi
    Route::get('/absensi', [GuruAbsensiController::class, 'index'])->name('guru.absensi.index');
    Route::get('/absensi/create', [GuruAbsensiController::class, 'create'])->name('guru.absensi.create');
    Route::post('/absensi', [GuruAbsensiController::class, 'store'])->name('guru.absensi.store');
    Route::get('/absensi/{kelas}', [GuruAbsensiController::class, 'show'])->name('guru.absensi.show');

    // Import Absensi
    Route::post('/absensi/import', [GuruAbsensiController::class, 'import'])->name('guru.absensi.import');
    Route::get('/absensi/template', [GuruAbsensiController::class, 'downloadTemplate'])->name('guru.absensi.template');
});

// =============================================
// ROUTE UNTUK ORANG TUA
// =============================================
Route::middleware(['auth', 'role:ortu'])->prefix('ortu')->group(function () {
    Route::get('/dashboard', [OrangTuaController::class, 'dashboard'])->name('ortu.dashboard');
    Route::get('/anak/{id}', [OrangTuaController::class, 'anak'])->name('ortu.anak');
});

// =============================================
// ROUTE GALLERY (Untuk Admin & Guru)
// =============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/gallery/manage', [GalleryController::class, 'manage'])->name('gallery.manage');
    Route::get('/gallery/{id}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::put('/gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::patch('/gallery/{id}/toggle', [GalleryController::class, 'toggle'])->name('gallery.toggle');
});

// =============================================
// ROUTE ABSENSI CEPAT (Untuk Admin & Guru)
// =============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/absensi-cepat', [AbsensiCepatController::class, 'index'])->name('absensi.cepat');
    Route::post('/absensi-cepat', [AbsensiCepatController::class, 'store'])->name('absensi.cepat.store');
    Route::post('/absensi-cepat/hadirkan-semua', [AbsensiCepatController::class, 'hadirkanSemua'])->name('absensi.cepat.hadirkan');
    Route::get('/absensi-cepat/siswa/{kelas}', [AbsensiCepatController::class, 'getSiswaByKelas'])->name('absensi.cepat.siswa');
});

// School Dashboard (Public)
Route::get('/school-dashboard', [GalleryController::class, 'index'])->name('school.dashboard');