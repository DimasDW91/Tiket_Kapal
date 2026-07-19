<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Models\Pemesanan;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TiketController extends Controller
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
        $tiket = Tiket::with(['pemesanan.jadwal.kapal', 'pemesanan.jadwal.pelabuhanAsal', 'pemesanan.jadwal.pelabuhanTujuan', 'pencetak'])
            ->orderByDesc('id')
            ->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.tiket', compact('tiket', 'sesiAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $pemesananList = Pemesanan::where('status_pemesanan', 'dibayar')
            ->orderByDesc('tanggal_pesan')
            ->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.tiket_create', compact('pemesananList', 'sesiAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'pemesanan_id'  => 'required|exists:pemesanan,id',
            'kode_tiket'    => 'required|string|max:30|unique:tiket,kode_tiket',
            'qr_code'       => 'nullable|string|max:255',
            'status_tiket'  => 'required|in:aktif,digunakan,dibatalkan,kadaluarsa',
        ]);

        $validated['qr_code'] = $validated['qr_code'] ?: Str::uuid()->toString();
        $validated['dicetak'] = false;

        Tiket::create($validated);

        return redirect()->route('tiket.index')
            ->with('success', 'Data tiket berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('tiket.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $tiket = Tiket::findOrFail($id);
        $pemesananList = Pemesanan::orderByDesc('tanggal_pesan')->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.tiket_edit', compact('tiket', 'pemesananList', 'sesiAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $tiket = Tiket::findOrFail($id);

        $validated = $request->validate([
            'pemesanan_id'  => 'required|exists:pemesanan,id',
            'kode_tiket'    => 'required|string|max:30|unique:tiket,kode_tiket,' . $tiket->id,
            'qr_code'       => 'nullable|string|max:255',
            'status_tiket'  => 'required|in:aktif,digunakan,dibatalkan,kadaluarsa',
        ]);

        $tiket->update($validated);

        return redirect()->route('tiket.index')
            ->with('success', 'Data tiket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $tiket = Tiket::findOrFail($id);
        $tiket->delete();

        return redirect()->route('tiket.index')
            ->with('success', 'Data tiket berhasil dihapus.');
    }

    /**
     * Tandai tiket sebagai sudah dicetak oleh kasir/admin yang sedang login.
     */
    public function cetak(string $id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->tandaiDicetak(Auth::id());

        return redirect()->route('tiket.index')
            ->with('success', 'Tiket ' . $tiket->kode_tiket . ' berhasil ditandai sudah dicetak.');
    }
}
