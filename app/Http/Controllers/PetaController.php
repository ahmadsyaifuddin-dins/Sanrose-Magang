<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        // Ambil instansi yang memiliki koordinat latitude dan longitude
        $lokasiInstansi = Instansi::whereNotNull('latitude')
                                  ->whereNotNull('longitude')
                                  // PERBAIKAN: Tambahkan 'id' di sini
                                  ->get(['id', 'nama_instansi', 'latitude', 'longitude', 'alamat']);

        return view('peta.index', compact('lokasiInstansi'));
    }
}
