<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    // Gunakan $guarded agar tidak perlu mendaftarkan setiap kolom
    protected $guarded = ['id'];

    /**
     * Relasi ke gambar instansi (satu instansi punya banyak gambar).
     */
    public function images()
    {
        return $this->hasMany(InstansiImage::class);
    }

    /**
     * Relasi ke user yang memilih instansi ini.
     */
    public function pilihan()
    {
        return $this->hasMany(InstansiPilihan::class);
    }
}