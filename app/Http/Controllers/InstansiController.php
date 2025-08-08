<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\InstansiImage;
use App\Models\InstansiPilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InstansiController extends Controller
{
    /**
     * Menampilkan daftar instansi untuk dipilih oleh user.
     */
    public function list()
    {
        $instansis = Instansi::with('images', 'pilihan.user')->get();
        
        // Cek apakah user sudah memilih instansi
        $pilihanUser = InstansiPilihan::where('user_id', Auth::id())->first();

        return view('instansi.list', compact('instansis', 'pilihanUser'));
    }

    /**
     * Aksi untuk user memilih instansi.
     */
    public function pilih($id)
    {
        // Pastikan user belum memilih
        $sudahPilih = InstansiPilihan::where('user_id', Auth::id())->exists();
        if ($sudahPilih) {
            return back()->with('error', 'Anda sudah memilih instansi dan tidak bisa mengubahnya.');
        }

        // Simpan pilihan
        InstansiPilihan::create([
            'user_id' => Auth::id(),
            'instansi_id' => $id,
            'is_fix' => true,
        ]);

        return back()->with('success', 'Anda berhasil memilih instansi.');
    }

    // --- METODE DI BAWAH INI HANYA UNTUK SUPERADMIN ---

    public function index()
    {
        $instansis = Instansi::with('images')->latest()->get();
        return view('instansi.index', compact('instansis'));
    }

    public function create()
    {
        return view('instansi.form');
    }

    /**
     * Menampilkan halaman detail untuk satu instansi.
     */
    public function show(Instansi $instansi)
    {
        // Eager load relasi untuk optimasi query
        $instansi->load('images', 'pilihan.user');

        return view('instansi.show', compact('instansi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'waktu_kunjungan' => 'required|date',
            'jam_kunjungan' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $instansi = Instansi::create($request->except('gambar'));

        // Logika upload gambar ke public/uploads/instansi
        if ($request->hasFile('gambar')) {
            $folderPath = public_path('uploads/instansi');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            foreach ($request->file('gambar') as $img) {
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move($folderPath, $filename);

                $instansi->images()->create([
                    'path_gambar' => 'uploads/instansi/' . $filename,
                ]);
            }
            
            // Otomatis set gambar pertama yang diupload sebagai default
            $firstImage = $instansi->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_default' => true]);
            }
        }

        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil ditambahkan.');
    }

    public function edit(Instansi $instansi)
    {
        return view('instansi.form', compact('instansi'));
    }

    public function update(Request $request, Instansi $instansi)
    {
        // Validasi dan update mirip dengan store()
        $instansi->update($request->except(['gambar', 'default_image_id']));
        // Anda bisa menambahkan logika untuk menghapus gambar lama jika diperlukan
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'waktu_kunjungan' => 'required|date',
            'jam_kunjungan' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $instansi->update($request->except('gambar'));

        // Logika upload gambar baru
        if ($request->hasFile('gambar')) {
            // ... (sama seperti di store)
            $folderPath = public_path('uploads/instansi');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            foreach ($request->file('gambar') as $img) {
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move($folderPath, $filename);

                $instansi->images()->create([
                    'path_gambar' => 'uploads/instansi/' . $filename,
                ]);
            }
            
            // Otomatis set gambar pertama yang diupload sebagai default
            $firstImage = $instansi->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_default' => true]);
            }
        }

        // Handle pemilihan gambar default
        if ($request->has('default_image_id')) {
            DB::transaction(function () use ($request, $instansi) {
                // 1. Reset semua gambar instansi ini menjadi tidak default
                $instansi->images()->update(['is_default' => false]);

                // 2. Set gambar yang dipilih menjadi default
                InstansiImage::where('id', $request->default_image_id)
                             ->where('instansi_id', $instansi->id) // Keamanan tambahan
                             ->update(['is_default' => true]);
            });
        }
        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil diperbarui.');
    }

    public function destroy(Instansi $instansi)
    {
        // Hapus file gambar dari server sebelum menghapus record DB
        foreach ($instansi->images as $img) {
            if (File::exists(public_path($img->path_gambar))) {
                File::delete(public_path($img->path_gambar));
            }
        }
        
        $instansi->delete(); // onDelete('cascade') akan menangani record di tabel lain
        return back()->with('success', 'Instansi berhasil dihapus.');
    }
}
