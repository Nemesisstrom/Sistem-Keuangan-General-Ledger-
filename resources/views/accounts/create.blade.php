@extends('layouts.app')

@section('title', 'Tambah Akun COA - SI Keuangan')

@section('content')
<div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header Kembali -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Akun Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan akun buku besar baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-indigo-600 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <p class="font-semibold">Terjadi kesalahan input:</p>
                <ul class="list-disc pl-5 mt-1 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Grid Input: Kode & Nama Akun -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Kode Akun</label>
                    <input type="text"
                           name="code"
                           id="code"
                           value="{{ old('code') }}"
                           required
                           placeholder="Contoh: 1-1100"
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('code') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm font-mono transition-all">
                    @error('code')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Nama Akun</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Contoh: Kas di Bank"
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('name') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid Input: Tipe Akun & Saldo Normal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Tipe Akun (Type)</label>
                    <select name="type"
                            id="type"
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('type') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                        <option value="">-- Pilih Tipe Akun --</option>
                        <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>Asset (Aset)</option>
                        <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>Liability (Kewajiban)</option>
                        <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>Equity (Ekuitas)</option>
                        <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>Revenue (Pendapatan)</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense (Beban)</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="normal_balance" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Saldo Normal (Normal Balance)</label>
                    <select name="normal_balance"
                            id="normal_balance"
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('normal_balance') border-red-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                        <option value="">-- Pilih Saldo Normal --</option>
                        <option value="debit" {{ old('normal_balance') == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="credit" {{ old('normal_balance') == 'credit' ? 'selected' : '' }}>Credit (Kredit)</option>
                    </select>
                    @error('normal_balance')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Input Keterangan / Deskripsi -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Deskripsi / Keterangan (Opsional)</label>
                <textarea name="description"
                          id="description"
                          rows="3"
                          placeholder="Catatan tambahan mengenai akun ini..."
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all">{{ old('description') }}</textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('accounts.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium transition-all">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-xl shadow-md shadow-indigo-200 transition-all">Simpan Akun</button>
            </div>
        </form>

    </div>
</div>
@endsection
