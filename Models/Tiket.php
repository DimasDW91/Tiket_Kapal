<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $table = 'tiket';

    protected $fillable = [
        'pemesanan_id',
        'kode_tiket',
        'qr_code',
        'status_tiket',
        'dicetak',
        'waktu_cetak',
        'dicetak_oleh',
    ];

    protected $casts = [
        'dicetak'     => 'boolean',
        'waktu_cetak' => 'datetime',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status_tiket', 'aktif');
    }

    public function scopeBelumDicetak($query)
    {
        return $query->where('dicetak', false);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAktif(): bool
    {
        return $this->status_tiket === 'aktif';
    }

    public function sudahDicetak(): bool
    {
        return $this->dicetak === true;
    }

    /**
     * Tandai tiket sebagai sudah dicetak oleh kasir.
     */
    public function tandaiDicetak(int $kasirId): void
    {
        $this->update([
            'dicetak'     => true,
            'waktu_cetak' => now(),
            'dicetak_oleh' => $kasirId,
        ]);
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    /** Kasir yang mencetak tiket ini */
    public function pencetak()
    {
        return $this->belongsTo(User::class, 'dicetak_oleh')->withDefault();
    }
}
