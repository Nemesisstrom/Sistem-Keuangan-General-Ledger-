@extends('layouts.auth')

@section('title', 'Login System')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">

    <!-- Header Modul -->
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-800">Sistem Keuangan</h1>
        <p class="text-sm text-slate-500 mt-1">General Ledger & Akuntansi</p>
    </div>

    <!-- Alert Success / Notification -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Login -->
    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email / Username</label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   class="w-full px-3 py-2 border @error('email') border-red-500 @else border-slate-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                   placeholder="nama@perusahaan.com">

            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi</label>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   class="w-full px-3 py-2 border @error('password') border-red-500 @else border-slate-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                   placeholder="••••••••">

            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between">
            <label class="flex items-center text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 text-white font-medium text-sm rounded-lg transition duration-150">
            Masuk ke Sistem
        </button>
    </form>

    <div class="mt-6 border-t border-slate-100 pt-4 text-center">
        <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Sistem Informasi Keuangan. All rights reserved.</p>
    </div>

</div>
@endsection
