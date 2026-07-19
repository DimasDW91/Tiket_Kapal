<?php

namespace App\Http\Controllers;

use App\Models\Penumpang;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenumpangController extends Controller
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
        $penumpang = Penumpang::with('user')->orderBy('nama_penumpang')->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.penumpang', compact('penumpang', 'sesiAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $sesiAktif = $this->sesiAktif();

        return view('tiket.penumpang_create', compact('sesiAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'nik'             => 'required|string|max:20|unique:penumpang,nik',
            'nama_penumpang'  => 'required|string|max:150',
            'jenis_kelamin'   => 'required|in:L,P',
            'tanggal_lahir'   => 'required|date|before:today',
        ]);

        Penumpang::create($validated);

        return redirect()->route('penumpang.index')
            ->with('success', 'Data penumpang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('penumpang.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $penumpang = Penumpang::findOrFail($id);
        $sesiAktif = $this->sesiAktif();

        return view('tiket.penumpang_edit', compact('penumpang', 'sesiAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $penumpang = Penumpang::findOrFail($id);

        $validated = $request->validate([
            'nik'             => 'required|string|max:20|unique:penumpang,nik,' . $penumpang->id,
            'nama_penumpang'  => 'required|string|max:150',
            'jenis_kelamin'   => 'required|in:L,P',
            'tanggal_lahir'   => 'required|date|before:today',
        ]);

        $penumpang->update($validated);

        return redirect()->route('penumpang.index')
            ->with('success', 'Data penumpang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $penumpang = Penumpang::findOrFail($id);

        // Cegah hapus penumpang yang sudah punya detail pemesanan
        if ($penumpang->detailPemesanan()->exists()) {
            return redirect()->route('penumpang.index')
                ->with('error', 'Penumpang tidak dapat dihapus karena sudah memiliki data pemesanan.');
        }

        $penumpang->delete();

        return redirect()->route('penumpang.index')
            ->with('success', 'Data penumpang berhasil dihapus.');
    }
}
