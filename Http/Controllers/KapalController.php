<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KapalController extends Controller
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
        $kapal = Kapal::orderBy('nama_kapal')->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.kapal', compact('kapal', 'sesiAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $sesiAktif = $this->sesiAktif();

        return view('tiket.kapal_create', compact('sesiAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'kode_kapal'  => 'required|string|max:20|unique:kapal,kode_kapal',
            'nama_kapal'  => 'required|string|max:150',
            'kapasitas'   => 'required|integer|min:1',
            'fasilitas'   => 'nullable|string',
        ]);

        Kapal::create($validated);

        return redirect()->route('kapal.index')
            ->with('success', 'Data kapal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('kapal.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $kapal = Kapal::findOrFail($id);
        $sesiAktif = $this->sesiAktif();

        return view('tiket.kapal_edit', compact('kapal', 'sesiAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $kapal = Kapal::findOrFail($id);

        $validated = $request->validate([
            'kode_kapal'  => 'required|string|max:20|unique:kapal,kode_kapal,' . $kapal->id,
            'nama_kapal'  => 'required|string|max:150',
            'kapasitas'   => 'required|integer|min:1',
            'fasilitas'   => 'nullable|string',
        ]);

        $kapal->update($validated);

        return redirect()->route('kapal.index')
            ->with('success', 'Data kapal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $kapal = Kapal::findOrFail($id);

        // Cegah hapus kapal yang masih dipakai di jadwal
        if ($kapal->jadwal()->exists()) {
            return redirect()->route('kapal.index')
                ->with('error', 'Kapal tidak dapat dihapus karena masih digunakan pada data jadwal.');
        }

        $kapal->delete();

        return redirect()->route('kapal.index')
            ->with('success', 'Data kapal berhasil dihapus.');
    }
}
