<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiKasir extends Model
{
    use HasFactory;

    protected $table = 'sesi_kasir';

    protected $fillable = [
        'kasir_id',
        'waktu_buka',
        'waktu_tutup',
        'total_transaksi',
        'jumlah_transaksi',
        'catatan',
        'status',
    ];

    protected $casts = [
        'waktu_buka'        => 'datetime',
        'waktu_tutup'       => 'datetime',
        'total_transaksi'   => 'decimal:2',
        'jumlah_transaksi'  => 'integer',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeBuka($query)
    {
        return $query->where('status', 'buka');
    }

    public function scopeTutup($query)
    {
        return $query->where('status', 'tutup');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isBuka(): bool
    {
        return $this->status === 'buka';
    }

    /**
     * Tutup sesi kasir dan rekap total dari pemesanan yang dilayani.
     */
    public function tutupSesi(?string $catatan = null): void
    {
        $pemesanan = Pemesanan::where('kasir_id', $this->kasir_id)
            ->where('channel', 'kasir')
            ->where('created_at', '>=', $this->waktu_buka)
            ->where('status_pemesanan', 'dibayar')
            ->get();

        $this->update([
            'waktu_tutup'       => now(),
            'total_transaksi'   => $pemesanan->sum('total_harga'),
            'jumlah_transaksi'  => $pemesanan->count(),
            'catatan'           => $catatan,
            'status'            => 'tutup',
        ]);
    }

    /**
     * Durasi sesi dalam menit (null jika masih buka).
     */
    public function durasiMenit(): ?int
    {
        if (!$this->waktu_tutup) {
            return null;
        }

        return (int) $this->waktu_buka->diffInMinutes($this->waktu_tutup);
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
