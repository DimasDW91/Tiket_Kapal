<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\JadwalKapal;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    /**
     * Ambil sesi kasir aktif untuk ditampilkan di topbar (khusus role kasir).
     */
    private function sesiAktif()
    {
        $user = Auth::user();

        if ($user->role === 'kasir') {
            return SesiKasir::where('kasir_id', $user->id)
                ->where('status', 'buka')
                ->latest()
                ->first();
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemesanan = Pemesanan::with(['jadwal.kapal', 'jadwal.pelabuhanAsal', 'jadwal.pelabuhanTujuan'])
            ->orderByDesc('tanggal_pesan')
            ->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.pemesanan', compact('pemesanan', 'sesiAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $jadwalList = JadwalKapal::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->orderByDesc('tanggal_berangkat')
            ->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.pemesanan_create', compact('jadwalList', 'sesiAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'kode_booking'      => 'required|string|max:30|unique:pemesanan,kode_booking',
            'jadwal_id'         => 'required|exists:jadwal_kapal,id',
            'nama_pemesan'      => 'required|string|max:150',
            'no_hp_pemesan'     => 'required|string|max:20',
            'channel'           => 'required|in:kasir,online',
            'tanggal_pesan'     => 'required|date',
            'jumlah_tiket'      => 'required|integer|min:1',
            'total_harga'       => 'required|numeric|min:0',
            'status_pemesanan'  => 'required|in:pending,dibayar,dibatalkan',
        ]);

        $validated['kasir_id'] = Auth::id();

        Pemesanan::create($validated);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Data pemesanan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('pemesanan.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $pemesanan = Pemesanan::findOrFail($id);
        $jadwalList = JadwalKapal::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->orderByDesc('tanggal_berangkat')
            ->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.pemesanan_edit', compact('pemesanan', 'jadwalList', 'sesiAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $pemesanan = Pemesanan::findOrFail($id);

        $validated = $request->validate([
            'kode_booking'      => 'required|string|max:30|unique:pemesanan,kode_booking,' . $pemesanan->id,
            'jadwal_id'         => 'required|exists:jadwal_kapal,id',
            'nama_pemesan'      => 'required|string|max:150',
            'no_hp_pemesan'     => 'required|string|max:20',
            'channel'           => 'required|in:kasir,online',
            'tanggal_pesan'     => 'required|date',
            'jumlah_tiket'      => 'required|integer|min:1',
            'total_harga'       => 'required|numeric|min:0',
            'status_pemesanan'  => 'required|in:pending,dibayar,dibatalkan',
        ]);

        $pemesanan->update($validated);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Data pemesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $pemesanan = Pemesanan::findOrFail($id);

        // Cegah hapus pemesanan yang sudah punya detail, pembayaran, atau tiket
        if ($pemesanan->detailPemesanan()->exists()
            || $pemesanan->pembayaran()->exists()
            || $pemesanan->tiket()->exists()) {
            return redirect()->route('pemesanan.index')
                ->with('error', 'Pemesanan tidak dapat dihapus karena sudah memiliki data terkait (detail/pembayaran/tiket).');
        }

        $pemesanan->delete();

        return redirect()->route('pemesanan.index')
            ->with('success', 'Data pemesanan berhasil dihapus.');
    }
}
