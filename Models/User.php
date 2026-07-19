<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeKasir($query)
    {
        return $query->where('role', 'kasir');
    }

    public function scopePelanggan($query)
    {
        return $query->where('role', 'pelanggan');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    /** Penumpang yang terdaftar atas akun ini */
    public function penumpang()
    {
        return $this->hasMany(Penumpang::class);
    }

    /** Pemesanan yang dibuat oleh user (online) */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'user_id');
    }

    /** Pemesanan yang dilayani kasir ini */
    public function pemesananDilayani()
    {
        return $this->hasMany(Pemesanan::class, 'kasir_id');
    }

    /** Sesi kasir milik user ini */
    public function sesiKasir()
    {
        return $this->hasMany(SesiKasir::class, 'kasir_id');
    }

    /** Tiket yang dicetak oleh kasir ini */
    public function tiketDicetak()
    {
        return $this->hasMany(Tiket::class, 'dicetak_oleh');
    }
}
