<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan form pendaftaran.
     */
    public function create()
    {
        return view('auth.register'); // Sesuaikan dengan lokasi file view Anda
    }

    /**
     * Menangani proses pendaftaran staff baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // 2. Simpan Data ke Database (Role otomatis accountant untuk pendaftaran staff)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'accountant',
            'is_active' => true,
        ]);

        // 3. Login-kan user setelah registrasi
        Auth::login($user);

        // 4. Redirect ke dashboard
        return redirect()->route('dashboard')->with('success', 'Akun berhasil didaftarkan.');
    }
}
