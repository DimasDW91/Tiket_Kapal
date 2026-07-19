<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelabuhan extends Model
{
    use HasFactory;

    protected $table = 'pelabuhan';

    protected $fillable = [
        'kode_pelabuhan',
        'nama_pelabuhan',
        'kota',
        'alamat',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    /** Jadwal kapal yang berangkat dari pelabuhan ini */
    public function jadwalAsal()
    {
        return $this->hasMany(JadwalKapal::class, 'pelabuhan_asal_id');
    }

    /** Jadwal kapal yang tiba di pelabuhan ini */
    public function jadwalTujuan()
    {
        return $this->hasMany(JadwalKapal::class, 'pelabuhan_tujuan_id');
    }
}
