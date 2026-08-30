<?php

use App\Http\Controllers\AbsensiCepatController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuruAbsensiController;
use App\Http\Controllers\GuruNilaiController;
use App\Http\Controllers\ImportAbsensiController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TunggakanSppController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Route Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pengaturan (ganti password sendiri) — semua role yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/pengaturan', [PengaturanController::class, 'show'])->name('pengaturan.show');
    Route::post('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::post('/pengaturan/whatsapp', [PengaturanController::class, 'updateWhatsapp'])->name('pengaturan.whatsapp');
});

// =============================================
// ROUTE UNTUK ADMIN (Super Admin)
// =============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AbsensiController::class, 'dashboard'])->name('admin.dashboard');

    // Register akun (HANYA ADMIN)
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

    // User Management (HANYA INI UNTUK ADMIN)
    Route::get('/users', [RegisterController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [RegisterController::class, 'delete'])->name('users.delete');
    Route::put('/users/{id}/role', [RegisterController::class, 'editRole'])->name('users.role');
    Route::put('/users/{id}/password', [RegisterController::class, 'changePassword'])->name('users.change-password');
    Route::get('/users/{id}/edit', [RegisterController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [RegisterController::class, 'update'])->name('users.update');

    // Import Siswa via Excel
    Route::post('/users/import', [RegisterController::class, 'importUsers'])->name('users.import');
    Route::get('/users/template', [RegisterController::class, 'downloadTemplate'])->name('users.template');

    // Absensi Manual
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
    Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/template', [AbsensiController::class, 'downloadTemplate'])->name('absensi.template');
    Route::post('/absensi/import', [AbsensiController::class, 'import'])->name('absensi.import');
    Route::get('/absensi/{kelas}', [AbsensiController::class, 'show'])->name('absensi.show');

    // Import Absensi
    Route::get('/import-absensi', [ImportAbsensiController::class, 'index'])->name('import.absensi');
    Route::post('/import-absensi', [ImportAbsensiController::class, 'import'])->name('import.absensi.store');
    Route::get('/import-absensi/template', [ImportAbsensiController::class, 'downloadTemplate'])->name('import.template');
});

// =============================================
// ROUTE UNTUK GURU & SISWA (hanya halaman yang boleh dilihat siswa)
// =============================================
Route::middleware(['auth', 'role:guru,siswa'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [GuruAbsensiController::class, 'dashboard'])->name('guru.dashboard');
    Route::get('/absensi', [GuruAbsensiController::class, 'index'])->name('guru.absensi.index');
    Route::get('/absensi/export', [GuruAbsensiController::class, 'export'])->name('guru.absensi.export');
});

// =============================================
// ROUTE KHUSUS GURU (input absensi & nilai)
// =============================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
    Route::get('/absensi/create', [GuruAbsensiController::class, 'create'])->name('guru.absensi.create');
    Route::post('/absensi', [GuruAbsensiController::class, 'store'])->name('guru.absensi.store');
    Route::get('/absensi/template', [GuruAbsensiController::class, 'downloadTemplate'])->name('guru.absensi.template');
    Route::post('/absensi/import', [GuruAbsensiController::class, 'import'])->name('guru.absensi.import');
    Route::get('/absensi/{kelas}', [GuruAbsensiController::class, 'show'])->name('guru.absensi.show');

    // Nilai (Setengah Semester & Akhir Semester)
    Route::get('/nilai/{periode}', [GuruNilaiController::class, 'index'])->name('guru.nilai.index');
    Route::post('/nilai/{periode}', [GuruNilaiController::class, 'store'])->name('guru.nilai.store');
    Route::post('/nilai/{periode}/import', [GuruNilaiController::class, 'import'])->name('guru.nilai.import');
    Route::get('/nilai/{periode}/template', [GuruNilaiController::class, 'downloadTemplate'])->name('guru.nilai.template');
    Route::delete('/nilai/{id}', [GuruNilaiController::class, 'destroy'])->name('guru.nilai.destroy');
});

// =============================================
// ROUTE TUNGGAKAN SPP (Khusus Guru)
// =============================================
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/tunggakan-spp', [TunggakanSppController::class, 'index'])->name('guru.tunggakan.index');
    Route::post('/tunggakan-spp', [TunggakanSppController::class, 'store'])->name('guru.tunggakan.store');
    Route::get('/tunggakan-spp/{id}/edit', [TunggakanSppController::class, 'edit'])->name('guru.tunggakan.edit');
    Route::put('/tunggakan-spp/{id}', [TunggakanSppController::class, 'update'])->name('guru.tunggakan.update');
    Route::delete('/tunggakan-spp/{id}', [TunggakanSppController::class, 'destroy'])->name('guru.tunggakan.destroy');
});

// =============================================
// ROUTE UNTUK ORANG TUA
// =============================================
Route::middleware(['auth', 'role:ortu'])->prefix('ortu')->group(function () {
    Route::get('/dashboard', [OrangTuaController::class, 'dashboard'])->name('ortu.dashboard');
    Route::get('/anak/{id}', [OrangTuaController::class, 'anak'])->name('ortu.anak');
    Route::get('/nilai', [OrangTuaController::class, 'nilai'])->name('ortu.nilai');
});

// =============================================
// ROUTE GALLERY (Untuk Admin & Guru)
// =============================================
Route::middleware(['auth', 'role:admin,guru'])->group(function () {
    Route::get('/gallery/manage', [GalleryController::class, 'manage'])->name('gallery.manage');
});

Route::middleware(['auth', 'role:admin,guru'])->group(function () {
    Route::get('/gallery/{id}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::put('/gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::patch('/gallery/{id}/toggle', [GalleryController::class, 'toggle'])->name('gallery.toggle');
});

// =============================================
// ROUTE ABSENSI CEPAT (Khusus Guru)
// =============================================
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/absensi-cepat', [AbsensiCepatController::class, 'index'])->name('absensi.cepat');
    Route::post('/absensi-cepat', [AbsensiCepatController::class, 'store'])->name('absensi.cepat.store');
    Route::post('/absensi-cepat/hadirkan-semua', [AbsensiCepatController::class, 'hadirkanSemua'])->name('absensi.cepat.hadirkan');
    Route::get('/absensi-cepat/siswa/{kelas}', [AbsensiCepatController::class, 'getSiswaByKelas'])->name('absensi.cepat.siswa');
});

// Halaman Download Aplikasi (PWA) - Public, bisa diakses tanpa login, bahasa awam
Route::get('/install', function () {
    return view('install');
})->name('pwa.install');

// School Dashboard (Public)
Route::get('/school-dashboard', [GalleryController::class, 'index'])->name('school.dashboard');
