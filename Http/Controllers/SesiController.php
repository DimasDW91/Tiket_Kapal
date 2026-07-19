<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    function index()
    {
        return view('perpustakaan/login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required'
        ], [
            'email.required'    => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi'
        ]);

        $infologin = [
            'name'     => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($infologin)) {
            return redirect('beranda');
        } else {
            return redirect('')->withErrors('Username dan Password yang dimasukan tidak sesuai')->withInput();
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
