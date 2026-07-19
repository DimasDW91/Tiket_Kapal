<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'kasir_id',
        'jadwal_id',
        'nama_pemesan',
        'no_hp_pemesan',
        'channel',
        'tanggal_pesan',
        'jumlah_tiket',
        'total_harga',
        'status_pemesanan',
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'total_harga'   => 'decimal:2',
        'jumlah_tiket'  => 'integer',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeKasir($query)
    {
        return $query->where('channel', 'kasir');
    }

    public function scopeOnline($query)
    {
        return $query->where('channel', 'online');
    }

    public function scopeDibayar($query)
    {
        return $query->where('status_pemesanan', 'dibayar');
    }

    public function scopePending($query)
    {
        return $query->where('status_pemesanan', 'pending');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isKasir(): bool
    {
        return $this->channel === 'kasir';
    }

    public function isDibayar(): bool
    {
        return $this->status_pemesanan === 'dibayar';
    }

    public function isBatal(): bool
    {
        return $this->status_pemesanan === 'dibatalkan';
    }

    /** Nama pemesan: dari akun user atau nama walk-in */
    public function getNamaPemesanDisplayAttribute(): string
    {
        return $this->user?->name ?? $this->nama_pemesan ?? '-';
    }

    /** Nomor HP pemesan: dari akun user atau input kasir */
    public function getNoHpDisplayAttribute(): string
    {
        return $this->user?->no_hp ?? $this->no_hp_pemesan ?? '-';
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    /** User pemilik pemesanan (null jika walk-in) */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /** Kasir yang memproses pemesanan */
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id')->withDefault();
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalKapal::class, 'jadwal_id');
    }

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'pemesanan_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id');
    }

    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'pemesanan_id');
    }
}
