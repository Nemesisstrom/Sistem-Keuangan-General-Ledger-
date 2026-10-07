@extends('layouts.app')

@section('title', 'Dashboard Keuangan')

@section('page-title', 'Dashboard Utama')
@section('page-subtitle', 'Ringkasan posisi keuangan, arus kas, dan indikator General Ledger.')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('journals.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-100 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Entri Jurnal Baru
        </a>
    </div>
@endsection

@section('content')

    <!-- Menghubungkan dan Memuat Tampilan Dashboard -->
    @include('dashboard')

@endsection

@section('content')

<!-- Stat Cards / Ringkasan Utama -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

    <!-- Total Kas & Bank -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kas & Bank</span>
            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800 mt-3">Rp {{ number_format($totalCash ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs text-emerald-600 font-medium mt-1">Status Aktif & Balanced</p>
    </div>

    <!-- Pendapatan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pendapatan</span>
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800 mt-3">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-400 mt-1">Akumulasi Periode Berjalan</p>
    </div>

    <!-- Total Beban -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Beban</span>
            <div class="p-2.5 bg-rose-50 text-rose-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800 mt-3">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-400 mt-1">Pengeluaran Operasional</p>
    </div>

    <!-- Laba Bersih -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Laba Bersih</span>
            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800 mt-3">Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs text-indigo-600 font-medium mt-1">Laba Bersih Operasional</p>
    </div>

</div>

<!-- Akses Cepat & Laporan -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Modul Navigasi Pintas -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Laporan & Pengelolaan</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <a href="{{ route('reports.general-ledger') }}" class="p-4 rounded-xl border border-slate-100 bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-200 transition-all group">
                <div class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center text-indigo-600 mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Buku Besar</h4>
                <p class="text-xs text-slate-500 mt-1">Rincian mutasi debit & kredit</p>
            </a>

            <a href="{{ route('reports.profit-loss') }}" class="p-4 rounded-xl border border-slate-100 bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-200 transition-all group">
                <div class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center text-indigo-600 mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Laporan Laba Rugi</h4>
                <p class="text-xs text-slate-500 mt-1">Pendapatan vs Beban</p>
            </a>

            <a href="{{ route('reports.balance-sheet') }}" class="p-4 rounded-xl border border-slate-100 bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-200 transition-all group">
                <div class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center text-indigo-600 mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Neraca Keuangan</h4>
                <p class="text-xs text-slate-500 mt-1">Aset, Liabilitas, & Ekuitas</p>
            </a>

        </div>
    </div>

    <!-- Ringkasan Master Chart of Accounts -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Chart of Accounts</h3>
                <a href="{{ route('accounts.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Kelola &rarr;</a>
            </div>
            <p class="text-xs text-slate-500 mb-4">Kelola dan atur struktur daftar akun perkiraan perusahaan Anda.</p>
        </div>
        <a href="{{ route('accounts.create') }}" class="w-full text-center py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition-colors">
            + Tambah Akun Baru
        </a>
    </div>

</div>

<!-- Tabel Transaksi Terbaru -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800">Transaksi Jurnal Terbaru</h3>
            <p class="text-xs text-slate-400 mt-0.5">Riwayat entri jurnal terakhir dalam sistem</p>
        </div>
        <a href="{{ route('journals.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Semua Jurnal &rarr;</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3.5">No. Voucher</th>
                    <th class="px-6 py-3.5">Tanggal</th>
                    <th class="px-6 py-3.5">Deskripsi</th>
                    <th class="px-6 py-3.5 text-right">Total Transaksi</th>
                    <th class="px-6 py-3.5 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentJournals ?? [] as $journal)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-semibold text-indigo-600">{{ $journal->journal_number }}</td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $journal->transaction_date }}</td>
                        <td class="px-6 py-4 text-slate-800 font-medium">{{ $journal->description }}</td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-800">Rp {{ number_format($journal->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-md border border-emerald-100">POSTED</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada transaksi jurnal yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
