@extends('layouts.auth')

@section('title', 'Masuk Ke Sistem')

@section('content')
<div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">

    <!-- Header Form -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Sistem Keuangan</h1>
        <p class="text-xs text-slate-500 mt-1">General Ledger & Financial Reporting</p>
    </div>

    <!-- Alert Notifikasi Sesi -->
    @if(session('success'))
        <div class="mb-5 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Login -->
    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Input Email -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Email</label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   class="w-full px-4 py-2.5 bg-slate-50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition-all"
                   placeholder="nama@perusahaan.com">

            @error('email')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   class="w-full px-4 py-2.5 bg-slate-50 border @error('password') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition-all"
                   placeholder="••••••••">

            @error('password')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Checkbox Remember Me -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span class="ml-2 text-xs">Ingat Saya</span>
            </label>
        </div>

        <!-- Tombol Submit -->
        <button type="submit"
                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium text-sm rounded-xl shadow-md shadow-indigo-200 transition-all">
            Masuk
        </button>
    </form>
    <!-- ... batas akhir form login ... -->
    </form>

    <!-- Navigasi Penghubung ke Halaman Register -->
    <div class="mt-6 border-t border-slate-100 pt-4 text-center">
        <p class="text-xs text-slate-500">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                Daftar sekarang
            </a>
        </p>
    </div>

</div>
@endsection
