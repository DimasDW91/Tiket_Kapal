<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';

    protected $fillable = [
        'kode_kapal',
        'nama_kapal',
        'kapasitas',
        'fasilitas',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    /** Semua jadwal keberangkatan kapal ini */
    public function jadwal()
    {
        return $this->hasMany(JadwalKapal::class, 'kapal_id');
    }
}
