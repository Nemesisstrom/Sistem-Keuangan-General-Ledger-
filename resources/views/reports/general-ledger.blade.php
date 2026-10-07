<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Page -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Buku Besar (General Ledger)</h1>
                <p class="text-sm text-gray-600">Rincian mutasi transaksi per akun COA dan cabang</p>
            </div>
            @if($selectedAccount && $journalItems->count() > 0)
                <button onclick="window.print()" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
                    🖨️ Cetak / PDF
                </button>
            @endif
        </div>

        <!-- Filter Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6 print:hidden">
            <form method="GET" action="{{ route('reports.general-ledger') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Filter Cabang -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Cabang</label>
                    <select name="branch_id" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Semua Cabang --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->code }} - {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Akun COA -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Akun COA *</label>
                    <select name="account_id" required class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Akun Rekening --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected' : '' }}>
                                {{ $acc->code }} - {{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Tanggal Selesai & Button -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sampai Tanggal</label>
                    <div class="flex gap-2">
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Laporan Buku Besar -->
        @if($selectedAccount)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Info Akun Header -->
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode & Nama Akun:</span>
                        <h2 class="text-lg font-bold text-gray-800">{{ $selectedAccount->code }} - {{ $selectedAccount->name }}</h2>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Normal / Tipe:</span>
                        <p class="text-sm font-semibold text-indigo-600 uppercase">{{ strtoupper($selectedAccount->normal_balance) }} ({{ $selectedAccount->type }})</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 border-b">
                                <th class="p-3 border-r w-28">Tanggal</th>
                                <th class="p-3 border-r w-36">No. Jurnal</th>
                                <th class="p-3 border-r w-24">Cabang</th>
                                <th class="p-3 border-r">Keterangan</th>
                                <th class="p-3 border-r text-right w-36">Debit (Rp)</th>
                                <th class="p-3 border-r text-right w-36">Kredit (Rp)</th>
                                <th class="p-3 text-right w-40">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris Saldo Awal -->
                            @php
                                $runningBalance = $openingBalance;
                                $totalDebit = 0;
                                $totalCredit = 0;
                            @endphp
                            <tr class="bg-yellow-50 font-semibold border-b">
                                <td class="p-3 border-r">{{ $startDate }}</td>
                                <td class="p-3 border-r" colspan="3">SALDO AWAL PERIODE</td>
                                <td class="p-3 border-r text-right">-</td>
                                <td class="p-3 border-r text-right">-</td>
                                <td class="p-3 text-right">{{ number_format($runningBalance, 2, ',', '.') }}</td>
                            </tr>

                            <!-- Baris Transaksi Mutasi -->
                            @forelse($journalItems as $item)
                                @php
                                    $debit = (float) $item->debit;
                                    $credit = (float) $item->credit;
                                    $totalDebit += $debit;
                                    $totalCredit += $credit;

                                    if ($selectedAccount->normal_balance === 'debit') {
                                        $runningBalance += ($debit - $credit);
                                    } else {
                                        $runningBalance += ($credit - $debit);
                                    }
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 border-r">{{ $item->journalEntry->date }}</td>
                                    <td class="p-3 border-r font-mono text-xs text-indigo-600">{{ $item->journalEntry->entry_number }}</td>
                                    <td class="p-3 border-r"><span class="px-2 py-0.5 bg-gray-200 text-gray-800 rounded text-xs font-semibold">{{ $item->journalEntry->branch->code }}</span></td>
                                    <td class="p-3 border-r">{{ $item->description ?: $item->journalEntry->description }}</td>
                                    <td class="p-3 border-r text-right">{{ $debit > 0 ? number_format($debit, 2, ',', '.') : '-' }}</td>
                                    <td class="p-3 border-r text-right">{{ $credit > 0 ? number_format($credit, 2, ',', '.') : '-' }}</td>
                                    <td class="p-3 text-right font-medium">{{ number_format($runningBalance, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-gray-500">Tidak ada transaksi mutasi pada periode tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <!-- Baris Total Mutasi & Saldo Akhir -->
                            <tr class="bg-gray-100 font-bold border-t-2 border-gray-300">
                                <td colspan="4" class="p-3 border-r text-right">TOTAL MUTASI PERIODE:</td>
                                <td class="p-3 border-r text-right text-green-700">Rp {{ number_format($totalDebit, 2, ',', '.') }}</td>
                                <td class="p-3 border-r text-right text-red-700">Rp {{ number_format($totalCredit, 2, ',', '.') }}</td>
                                <td class="p-3 text-right"></td>
                            </tr>
                            <tr class="bg-indigo-50 font-extrabold text-indigo-900 border-t">
                                <td colspan="6" class="p-3 border-r text-right">SALDO AKHIR PERIODE:</td>
                                <td class="p-3 text-right text-indigo-700">Rp {{ number_format($runningBalance, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 text-blue-800 p-6 rounded-lg text-center">
                Silakan pilih akun COA pada filter di atas untuk menampilkan rincian Buku Besar.
            </div>
        @endif
    </div>
</x-app-layout>
