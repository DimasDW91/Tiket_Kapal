<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SesiKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Ambil sesi kasir aktif untuk ditampilkan di topbar (khusus role kasir).
     * (Route ini admin-only, tapi helper tetap disediakan agar layout topbar konsisten.)
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
        $users = User::orderBy('name')->get();
        $sesiAktif = $this->sesiAktif();

        return view('tiket.users', compact('users', 'sesiAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sesiAktif = $this->sesiAktif();

        return view('tiket.users_create', compact('sesiAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|max:150|unique:users,email',
            'password'  => ['required', 'confirmed', Password::min(8)],
            'no_hp'     => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
            'role'      => 'required|in:admin,kasir,pelanggan',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $sesiAktif = $this->sesiAktif();

        return view('tiket.users_edit', compact('user', 'sesiAktif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|max:150|unique:users,email,' . $user->id,
            'password'  => ['nullable', 'confirmed', Password::min(8)],
            'no_hp'     => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
            'role'      => 'required|in:admin,kasir,pelanggan',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Cegah admin menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cegah hapus user yang masih punya data transaksi terkait
        if ($user->pemesanan()->exists()
            || $user->pemesananDilayani()->exists()
            || $user->sesiKasir()->exists()
            || $user->tiketDicetak()->exists()
            || $user->penumpang()->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'Pengguna tidak dapat dihapus karena masih memiliki data terkait di sistem.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}
