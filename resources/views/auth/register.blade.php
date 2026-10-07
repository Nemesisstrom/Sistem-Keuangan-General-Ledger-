@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">

    <!-- Header Form -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Daftar Akun Baru</h1>
        <p class="text-xs text-slate-500 mt-1">Sistem Informasi Keuangan (General Ledger)</p>
    </div>

    <!-- Form Registrasi -->
    <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Input Nama Lengkap -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   class="w-full px-4 py-2.5 bg-slate-50 border @error('name') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all"
                   placeholder="Nama Pengguna">

            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Email -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   class="w-full px-4 py-2.5 bg-slate-50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all"
                   placeholder="nama@perusahaan.com">

            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   class="w-full px-4 py-2.5 bg-slate-50 border @error('password') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all"
                   placeholder="Minimal 8 karakter">

            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Konfirmasi Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
            <input type="password"
                   name="password_confirmation"
                   id="password_confirmation"
                   required
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all"
                   placeholder="Ulangi kata sandi">
        </div>

        <!-- Tombol Submit -->
        <button type="submit"
                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium text-sm rounded-xl shadow-md shadow-indigo-200 transition-all mt-2">
            Daftar Sekarang
        </button>
    </form>

    <!-- Navigasi Penghubung ke Halaman Login -->
    <div class="mt-6 border-t border-slate-100 pt-4 text-center">
        <p class="text-xs text-slate-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                Masuk di sini
            </a>
        </p>
    </div>

</div>
@endsection
