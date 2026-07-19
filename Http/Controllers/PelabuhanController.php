<?php

namespace App\Http\Controllers;

use App\Models\Pelabuhan;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelabuhanController extends Controller
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
        $pelabuhan = Pelabuhan::orderBy('nama_pelabuhan')->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.pelabuhan', compact('pelabuhan', 'sesiAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $sesiAktif = $this->sesiAktif();

        return view('tiket.pelabuhan_create', compact('sesiAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'kode_pelabuhan'  => 'required|string|max:20|unique:pelabuhan,kode_pelabuhan',
            'nama_pelabuhan'  => 'required|string|max:150',
            'kota'            => 'required|string|max:100',
            'alamat'          => 'required|string',
        ]);

        Pelabuhan::create($validated);

        return redirect()->route('pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('pelabuhan.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $pelabuhan = Pelabuhan::findOrFail($id);
        $sesiAktif = $this->sesiAktif();

        return view('tiket.pelabuhan_edit', compact('pelabuhan', 'sesiAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $pelabuhan = Pelabuhan::findOrFail($id);

        $validated = $request->validate([
            'kode_pelabuhan'  => 'required|string|max:20|unique:pelabuhan,kode_pelabuhan,' . $pelabuhan->id,
            'nama_pelabuhan'  => 'required|string|max:150',
            'kota'            => 'required|string|max:100',
            'alamat'          => 'required|string',
        ]);

        $pelabuhan->update($validated);

        return redirect()->route('pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $pelabuhan = Pelabuhan::findOrFail($id);

        // Cegah hapus pelabuhan yang masih dipakai di jadwal kapal
        if ($pelabuhan->jadwalAsal()->exists() || $pelabuhan->jadwalTujuan()->exists()) {
            return redirect()->route('pelabuhan.index')
                ->with('error', 'Pelabuhan tidak dapat dihapus karena masih digunakan pada data jadwal kapal.');
        }

        $pelabuhan->delete();

        return redirect()->route('pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil dihapus.');
    }
}
