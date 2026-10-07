<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Page -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Laba Rugi (Profit & Loss)</h1>
                <p class="text-sm text-gray-600">
                    Cabang: <span class="font-semibold text-indigo-600">{{ $selectedBranch ? $selectedBranch->code . ' - ' . $selectedBranch->name : 'Konsolidasi (Seluruh Cabang)' }}</span>
                </p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 print:hidden">
                🖨️ Cetak / PDF
            </button>
        </div>

        <!-- Filter Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6 print:hidden">
            <form method="GET" action="{{ route('reports.profit-loss') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Filter Cabang -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Pilih Cabang</label>
                    <select name="branch_id" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Konsolidasi (Semua Cabang) --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->code }} - {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dari Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                        Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Document Sheet Laba Rugi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <!-- Header Dokumen Cetak -->
            <div class="text-center border-b pb-6 mb-6">
                <h2 class="text-xl font-bold uppercase tracking-wider text-gray-900">SISTEM INFORMASI KEUANGAN</h2>
                <h3 class="text-lg font-semibold text-gray-700">LAPORAN LABA RUGI</h3>
                <p class="text-sm text-gray-500">
                    Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}
                </p>
            </div>

            <div class="space-y-8">
                <!-- 1. PENDAPATAN (REVENUE) -->
                <div>
                    <h3 class="text-md font-bold uppercase text-gray-800 border-b-2 border-gray-800 pb-1 mb-3">
                        1. PENDAPATAN OPERASIONAL
                    </h3>
                    <table class="w-full text-sm">
                        <tbody>
                            @forelse($revenues as $rev)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 pl-4 text-gray-600 font-mono w-28">{{ $rev->code }}</td>
                                    <td class="py-2 text-gray-800">{{ $rev->name }}</td>
                                    <td class="py-2 text-right font-medium w-48">Rp {{ number_format($rev->amount, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-2 pl-4 text-gray-400 italic">Tidak ada transaksi pendapatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-bold bg-green-50 text-green-900">
                                <td colspan="2" class="py-3 pl-4 uppercase">TOTAL PENDAPATAN</td>
                                <td class="py-3 text-right text-base">Rp {{ number_format($totalRevenue, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- 2. BEBAN & BIAYA (EXPENSES) -->
                <div>
                    <h3 class="text-md font-bold uppercase text-gray-800 border-b-2 border-gray-800 pb-1 mb-3">
                        2. BEBAN OPERASIONAL & ADMIN
                    </h3>
                    <table class="w-full text-sm">
                        <tbody>
                            @forelse($expenses as $exp)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 pl-4 text-gray-600 font-mono w-28">{{ $exp->code }}</td>
                                    <td class="py-2 text-gray-800">{{ $exp->name }}</td>
                                    <td class="py-2 text-right font-medium w-48">Rp {{ number_format($exp->amount, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-2 pl-4 text-gray-400 italic">Tidak ada transaksi beban.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-bold bg-red-50 text-red-900">
                                <td colspan="2" class="py-3 pl-4 uppercase">TOTAL BEBAN OPERASIONAL</td>
                                <td class="py-3 text-right text-base">Rp {{ number_format($totalExpense, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- 3. LABA / RUGI BERSIH (NET PROFIT / LOSS) -->
                <div class="border-t-2 border-gray-900 pt-4">
                    <div class="p-4 rounded-lg flex justify-between items-center {{ $netProfit >= 0 ? 'bg-indigo-50 border border-indigo-200' : 'bg-red-100 border border-red-300' }}">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-600">HASIL AKHIR PERIODE</span>
                            <h4 class="text-xl font-extrabold uppercase {{ $netProfit >= 0 ? 'text-indigo-900' : 'text-red-900' }}">
                                {{ $netProfit >= 0 ? 'LABA BERSIH (NET PROFIT)' : 'RUGI BERSIH (NET LOSS)' }}
                            </h4>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black {{ $netProfit >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                                Rp {{ number_format($netProfit, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
