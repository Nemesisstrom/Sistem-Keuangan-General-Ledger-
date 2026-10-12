@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Form Pengeluaran Kas / Bank</h1>

    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <form action="{{ route('cash.out.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full border rounded p-2 text-sm border-gray-300">
            </div>

            @if(!auth()->user()->branch_id)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Cabang</label>
                    <select name="branch_id" class="w-full border rounded p-2 text-sm border-gray-300">
                        <option value="">-- Pusat (Semua Cabang) --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Akun Beban / Pengeluaran (Debit)</label>
                <select name="expense_account_id" required class="w-full border rounded p-2 text-sm border-gray-300">
                    @foreach($expenseAccounts as $account)
                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Akun Kas / Bank (Kredit)</label>
                <select name="cash_account_id" required class="w-full border rounded p-2 text-sm border-gray-300">
                    @foreach($cashAccounts as $account)
                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                <input type="number" name="amount" min="1" required class="w-full border rounded p-2 text-sm border-gray-300">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <input type="text" name="description" required class="w-full border rounded p-2 text-sm border-gray-300">
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded text-sm">Simpan Pengeluaran</button>
        </form>
    </div>
</div>
@endsection
