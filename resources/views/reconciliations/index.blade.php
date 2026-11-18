<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
</div>
@extends('layouts.app')

@section('title', 'Rekonsiliasi Bank - SI Keuangan')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{
    selectedAccountId: '{{ request('account_id') }}',
    bookBalance: '{{ $calculatedBookBalance ?? 0 }}',
    bankBalance: 0,
    get difference() {
        return (parseFloat(this.bankBalance || 0) - parseFloat(this.bookBalance || 0)).toFixed(2);
    }
}">

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Rekonsiliasi Bank (Bank Reconciliation)</h1>
            <p class="text-sm text-slate-500 mt-1">Cocokkan saldo buku kas/bank perusahaan dengan laporan rekening koran bank.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    <!-- Form Cek & Hitung Saldo Buku -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <h2 class="text-base font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100">1. Pilih Akun & Periode Statement</h2>
        <form action="{{ route('reconciliations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Akun Kas / Bank</label>
                <select name="account_id" x-model="selectedAccountId" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="">-- Pilih Akun Bank --</option>
                    @foreach($bankAccounts as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->code }} - {{ $acc->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Per Tanggal Statement</label>
                <input type="date" name="statement_date" value="{{ request('statement_date', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
            </div>
            <div>
                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-md transition-all">
                    Muat Saldo Buku
                </button>
            </div>
        </form>
    </div>

    @if($selectedAccount)
    <!-- Form Input Saldo Koran & Simpan Rekonsiliasi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <h2 class="text-base font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100">2. Kalkulasi & Catat Rekonsiliasi: <span class="text-indigo-600">{{ $selectedAccount->name }}</span></h2>

        <form action="{{ route('reconciliations.process') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="account_id" value="{{ $selectedAccount->id }}">
            <input type="hidden" name="statement_date" value="{{ request('statement_date') }}">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Saldo Buku (Book Balance)</label>
                    <input type="text" name="book_balance" readonly value="{{ $calculatedBookBalance }}" class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-mono font-bold text-slate-700">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Saldo Rekening Koran (Bank Statement)</label>
                    <input type="number" step="0.01" name="bank_statement_balance" x-model="bankBalance" required placeholder="0.00" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Selisih (Difference)</label>
                    <input type="text" readonly x-bind:value="'Rp ' + Number(difference).toLocaleString('id-ID')" class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-mono font-bold" :class="difference == 0 ? 'text-emerald-600' : 'text-rose-600'">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Catatan / Keterangan Rekonsiliasi</label>
                <textarea name="notes" rows="2" placeholder="Catat penyesuaian cek dalam perjalanan atau biaya administrasi bank..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-md transition-all">
                    Simpan Hasil Rekonsiliasi
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Riwayat Rekonsiliasi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800 text-base">Riwayat Rekonsiliasi Bank</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-xs border-b border-slate-100">
                        <th class="py-3 px-6 font-semibold">Tanggal Statement</th>
                        <th class="py-3 px-6 font-semibold">Akun Bank</th>
                        <th class="py-3 px-6 font-semibold text-right">Saldo Buku</th>
                        <th class="py-3 px-6 font-semibold text-right">Saldo Koran</th>
                        <th class="py-3 px-6 font-semibold text-right">Selisih</th>
                        <th class="py-3 px-6 font-semibold">Dibuat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($reconciliations as $recon)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($recon->statement_date)->format('d/m/Y') }}</td>
                            <td class="py-4 px-6 font-medium text-slate-900">{{ $recon->account->code }} - {{ $recon->account->name }}</td>
                            <td class="py-4 px-6 text-right font-mono">Rp {{ number_format($recon->book_balance, 2, ',', '.') }}</td>
                            <td class="py-4 px-6 text-right font-mono">Rp {{ number_format($recon->bank_statement_balance, 2, ',', '.') }}</td>
                            <td class="py-4 px-6 text-right font-mono font-bold {{ $recon->difference == 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format($recon->difference, 2, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-slate-500 text-xs">{{ $recon->creator->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">Belum ada riwayat rekonsiliasi bank tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $reconciliations->links() }}
        </div>
    </div>

</div>
@endsection
