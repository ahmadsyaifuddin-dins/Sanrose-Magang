<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\PetaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // Arahkan ke halaman login jika belum login
    return view('auth.login');
});

// Rute Dashboard bawaan Breeze
Route::get('/dashboard', function () {
    // Cek role user dan arahkan ke view yang sesuai
    // if (auth()->user()->role === 'superadmin') {
    //     // Jika superadmin, mungkin lebih baik arahkan ke manajemen instansi
    //     return redirect()->route('instansi.index');
    // }
    // Jika maganger, arahkan ke daftar instansi untuk memilih
    // return redirect()->route('instansi.list');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


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
    // Middleware 'superadmin' yang sudah kita buat sebelumnya tetap digunakan
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
