<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    use HasFactory;

    protected $table = 'penumpang';

    protected $fillable = [
        'user_id',
        'nik',
        'nama_penumpang',
        'jenis_kelamin',
        'tanggal_lahir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'user_id'       => 'integer',
    ];

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /** Apakah penumpang ini adalah walk-in (tidak punya akun) */
    public function isWalkIn(): bool
    {
        return is_null($this->user_id);
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    /** Akun user pemilik data penumpang (nullable untuk walk-in) */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /** Detail pemesanan yang melibatkan penumpang ini */
    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'penumpang_id');
    }
}
