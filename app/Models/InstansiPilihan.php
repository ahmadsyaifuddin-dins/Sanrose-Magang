<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiPilihan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Relasi ke instansi yang dipilih.
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Relasi ke user yang memilih.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}