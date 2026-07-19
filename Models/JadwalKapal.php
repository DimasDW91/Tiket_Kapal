<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKapal extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kapal';

    protected $fillable = [
        'kapal_id',
        'pelabuhan_asal_id',
        'pelabuhan_tujuan_id',
        'tanggal_berangkat',
        'tanggal_tiba',
        'harga_tiket',
        'kuota',
        'status',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'datetime',
        'tanggal_tiba'      => 'datetime',
        'harga_tiket'       => 'decimal:2',
        'kuota'             => 'integer',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isTersedia(): bool
    {
        return $this->status === 'tersedia';
    }

    /** Sisa kuota yang belum terpesan */
    public function sisaKuota(): int
    {
        $terpesan = $this->pemesanan()
            ->whereNotIn('status_pemesanan', ['dibatalkan'])
            ->sum('jumlah_tiket');

        return max(0, $this->kuota - $terpesan);
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'kapal_id');
    }

    public function pelabuhanAsal()
    {
        return $this->belongsTo(Pelabuhan::class, 'pelabuhan_asal_id');
    }

    public function pelabuhanTujuan()
    {
        return $this->belongsTo(Pelabuhan::class, 'pelabuhan_tujuan_id');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'jadwal_id');
    }
}
