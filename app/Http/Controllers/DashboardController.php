<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instansi;
use App\Models\InstansiPilihan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data statistik.
     */
    public function index()
    {
        // Data umum untuk kartu statistik
        $totalInstansi = Instansi::count();
        $totalMaganger = User::where('role', 'maganger')->count();
        $totalPilihan = InstansiPilihan::count();

        // Data spesifik yang akan dikirim ke view, diinisialisasi null
        $pilihanUser = null;
        $instansiDenganPilihan = null;
        $recentLogins = null; // Inisialisasi variabel baru

        // Cek role user yang sedang login
        if (Auth::user()->role == 'maganger') {
            // Jika maganger, cari instansi yang sudah dia pilih
            $pilihanUser = InstansiPilihan::with('instansi')->where('user_id', Auth::id())->first();
        } 
        elseif (Auth::user()->role == 'superadmin') {
            // Jika superadmin, ambil semua instansi yang memiliki pemilih
            $instansiDenganPilihan = Instansi::has('pilihan')->with('pilihan.user')->get();

            // --- QUERY BARU: Ambil 5 user yang login terbaru ---
            $recentLogins = User::whereNotNull('last_login_at')
                                ->orderBy('last_login_at', 'desc')
                                ->take(5)
                                ->get();
        }

        // Kirim semua data ke view 'dashboard'
        return view('dashboard', compact(
            'totalInstansi',
            'totalMaganger',
            'totalPilihan',
            'pilihanUser',
            'instansiDenganPilihan',
            'recentLogins'
        ));
    }
}
