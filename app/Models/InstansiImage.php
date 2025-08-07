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
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }
}
