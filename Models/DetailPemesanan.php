<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanan';

    protected $fillable = [
        'pemesanan_id',
        'penumpang_id',
        'nomor_kursi',
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    public function penumpang()
    {
        return $this->belongsTo(Penumpang::class, 'penumpang_id');
    }
}
