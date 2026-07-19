<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pemesanan_id',
        'metode_pembayaran',
        'jumlah_bayar',
        'jumlah_diterima',
        'kembalian',
        'nomor_struk',
        'tanggal_bayar',
        'bukti_pembayaran',
        'status_pembayaran',
    ];

    protected $casts = [
        'jumlah_bayar'    => 'decimal:2',
        'jumlah_diterima' => 'decimal:2',
        'kembalian'       => 'decimal:2',
        'tanggal_bayar'   => 'datetime',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeTunai($query)
    {
        return $query->where('metode_pembayaran', 'tunai');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isLunas(): bool
    {
        return $this->status_pembayaran === 'lunas';
    }

    public function isTunai(): bool
    {
        return $this->metode_pembayaran === 'tunai';
    }

    /**
     * Hitung kembalian otomatis dari jumlah yang diterima.
     * Dipanggil saat kasir input jumlah uang diterima.
     */
    public function hitungKembalian(): void
    {
        if ($this->isTunai() && $this->jumlah_diterima !== null) {
            $this->kembalian = max(0, $this->jumlah_diterima - $this->jumlah_bayar);
        }
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }
}
