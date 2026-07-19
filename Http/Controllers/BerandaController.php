<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\Penumpang;
use App\Models\Pemesanan;
use App\Models\Tiket;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin dan kasir melihat statistik yang sama
        if (in_array($user->role, ['admin', 'kasir'])) {
            $jumlahKapal      = Kapal::count();
            $jumlahPenumpang  = Penumpang::count();
            $jumlahPemesanan  = Pemesanan::count();
            $jumlahTiket      = Tiket::count();

            // Statistik tambahan untuk admin
            $pemesananHariIni = Pemesanan::whereDate('tanggal_pesan', today())->count();
            $pendapatanHariIni = Pemesanan::whereDate('tanggal_pesan', today())
                ->where('status_pemesanan', 'dibayar')
                ->sum('total_harga');

            // Sesi kasir aktif (hanya relevan untuk kasir)
            $sesiAktif = null;
            if ($user->role === 'kasir') {
                $sesiAktif = SesiKasir::where('kasir_id', $user->id)
                    ->where('status', 'buka')
                    ->latest()
                    ->first();
            }

            return view('tiket/beranda', compact(
                'jumlahKapal',
                'jumlahPenumpang',
                'jumlahPemesanan',
                'jumlahTiket',
                'pemesananHariIni',
                'pendapatanHariIni',
                'sesiAktif'
            ));
        }

        // Petugas / role lain — redirect ke beranda juga (bisa dikembangkan)
        return redirect()->route('beranda');
    }
}
