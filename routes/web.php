<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE BARU UNTUK HALAMAN DEPAN ---
Route::get('/', function () {
    // Jika pengguna sudah login, arahkan langsung ke dashboard.
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Jika belum login (guest), tampilkan halaman welcome.
    return view('welcome');
})->name('welcome');


// Rute Dashboard sekarang menunjuk ke DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Grup rute yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    // Rute Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- RUTE APLIKASI KITA ---

    // Rute yang bisa diakses semua role yang login
    Route::get('/instansi/list', [InstansiController::class, 'list'])->name('instansi.list');
    Route::post('/instansi/pilih/{id}', [InstansiController::class, 'pilih'])->name('instansi.pilih');
    Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
    Route::get('/instansi/detail/{instansi}', [InstansiController::class, 'show'])->name('instansi.show');

    // Rute untuk Superadmin (CRUD Instansi)
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/instansi/manage', [InstansiController::class, 'index'])->name('instansi.index');
        Route::get('/instansi/create', [InstansiController::class, 'create'])->name('instansi.create');
        Route::post('/instansi', [InstansiController::class, 'store'])->name('instansi.store');
        Route::get('/instansi/{instansi}/edit', [InstansiController::class, 'edit'])->name('instansi.edit');
        Route::put('/instansi/{instansi}', [InstansiController::class, 'update'])->name('instansi.update');
        Route::delete('/instansi/{instansi}', [InstansiController::class, 'destroy'])->name('instansi.destroy');
    });
});

// Rute fallback untuk otentikasi dari Breeze
require __DIR__.'/auth.php';
