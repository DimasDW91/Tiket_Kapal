<?php

namespace App\Http\Controllers;

use App\Models\JadwalKapal;
use App\Models\Kapal;
use App\Models\Pelabuhan;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKapalController extends Controller
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
        $jadwal = JadwalKapal::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->orderByDesc('tanggal_berangkat')
            ->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.jadwal', compact('jadwal', 'sesiAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $kapalList = Kapal::orderBy('nama_kapal')->get();
        $pelabuhanList = Pelabuhan::orderBy('nama_pelabuhan')->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.jadwal_create', compact('kapalList', 'pelabuhanList', 'sesiAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'kapal_id'             => 'required|exists:kapal,id',
            'pelabuhan_asal_id'    => 'required|exists:pelabuhan,id|different:pelabuhan_tujuan_id',
            'pelabuhan_tujuan_id'  => 'required|exists:pelabuhan,id',
            'tanggal_berangkat'    => 'required|date',
            'tanggal_tiba'         => 'required|date|after:tanggal_berangkat',
            'harga_tiket'          => 'required|numeric|min:0',
            'kuota'                => 'required|integer|min:1',
            'status'               => 'required|in:tersedia,penuh,dibatalkan,selesai',
        ]);

        JadwalKapal::create($validated);

        return redirect()->route('jadwal.index')
            ->with('success', 'Data jadwal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('jadwal.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $jadwal = JadwalKapal::findOrFail($id);
        $kapalList = Kapal::orderBy('nama_kapal')->get();
        $pelabuhanList = Pelabuhan::orderBy('nama_pelabuhan')->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.jadwal_edit', compact('jadwal', 'kapalList', 'pelabuhanList', 'sesiAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $jadwal = JadwalKapal::findOrFail($id);

        $validated = $request->validate([
            'kapal_id'             => 'required|exists:kapal,id',
            'pelabuhan_asal_id'    => 'required|exists:pelabuhan,id|different:pelabuhan_tujuan_id',
            'pelabuhan_tujuan_id'  => 'required|exists:pelabuhan,id',
            'tanggal_berangkat'    => 'required|date',
            'tanggal_tiba'         => 'required|date|after:tanggal_berangkat',
            'harga_tiket'          => 'required|numeric|min:0',
            'kuota'                => 'required|integer|min:1',
            'status'               => 'required|in:tersedia,penuh,dibatalkan,selesai',
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')
            ->with('success', 'Data jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $jadwal = JadwalKapal::findOrFail($id);

        // Cegah hapus jadwal yang sudah punya pemesanan
        if ($jadwal->pemesanan()->exists()) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Jadwal tidak dapat dihapus karena sudah memiliki data pemesanan.');
        }

        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Data jadwal berhasil dihapus.');
    }
}
