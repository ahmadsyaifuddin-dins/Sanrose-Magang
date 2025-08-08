<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiImage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Relasi ke instansi induk.
     */
    public function images()
    {
        return $this->hasMany(InstansiImage::class);
    }

    public function pilihan()
    {
        return $this->hasMany(InstansiPilihan::class);
    }

    /**
     * Accessor kustom untuk mendapatkan URL gambar default.
     * Nama method ini akan diakses sebagai properti: $instansi->default_image_url
     */
    public function getDefaultImageUrlAttribute(): string
    {
        // 1. Cari gambar yang ditandai sebagai default
        $defaultImage = $this->images()->where('is_default', true)->first();
        if ($defaultImage) {
            return asset($defaultImage->path_gambar);
        }

        // 2. Jika tidak ada, ambil gambar pertama sebagai fallback
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset($firstImage->path_gambar);
        }

        // 3. Jika tidak ada gambar sama sekali, tampilkan placeholder
        return 'https://placehold.co/600x400/e2e8f0/a0aec0?text=No+Image';
    }
}
