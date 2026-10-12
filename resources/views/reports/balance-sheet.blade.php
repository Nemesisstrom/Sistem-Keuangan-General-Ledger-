@extends('layouts.app')

@section('title', 'Laporan Posisi Keuangan / Neraca - SI Keuangan')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header & Tombol Cetak -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Posisi Keuangan (Balance Sheet)</h1>
            <p class="text-sm text-slate-500 mt-1">Rincian aset, kewajiban, dan ekuitas perusahaan pada tanggal tertentu.</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / PDF
        </button>
    </div>

    <!-- Form Filter Tanggal & Cabang -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form action="{{ route('reports.balance-sheet') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Per Tanggal (As of Date)</label>
                <input type="date" name="as_of_date" value="{{ $asOfDate }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Cabang (Opsional)</label>
                <select name="branch_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="">-- Semua Cabang (Konsolidasian) --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-md transition-all">
                    Tampilkan Laporan
                </button>
            </div>

        </form>
    </div>

    <!-- Container Utama Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6 p-6 sm:p-8">

        <div class="text-center pb-6 mb-6 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-900">SISTEM INFORMASI KEUANGAN</h2>
            <h3 class="text-lg font-semibold text-indigo-600 mt-1">Laporan Posisi Keuangan (Neraca)</h3>
            <p class="text-xs text-slate-500 mt-1">Per Tanggal: {{ \Carbon\Carbon::parse($asOfDate)->translatedFormat('d F Y') }}</p>
            @if($selectedBranch)
                <p class="text-xs font-medium text-slate-600 mt-0.5">Cabang: {{ $selectedBranch->name }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

            <!-- KOLOM KIRI: ASET (ASSETS) -->
            <div class="space-y-6">
                <div>
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider bg-slate-50 px-4 py-2.5 rounded-xl mb-3">1. Aset (Assets)</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse($assets as $asset)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-3 px-4 font-mono text-slate-500 w-28">{{ $asset->code }}</td>
                                        <td class="py-3 px-4">{{ $asset->name }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-medium">Rp {{ number_format($asset->amount, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-slate-400 text-sm">Tidak ada data aset.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="font-bold text-slate-900 border-t border-slate-200 bg-slate-50/50">
                                    <td colspan="2" class="py-3 px-4 text-right uppercase text-xs">Total Aset:</td>
                                    <td class="py-3 px-4 text-right font-mono text-indigo-600">Rp {{ number_format($totalAsset, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: KEWAJIBAN & EKUITAS (LIABILITIES & EQUITY) -->
            <div class="space-y-6">

                <!-- Kewajiban (Liabilities) -->
                <div>
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider bg-slate-50 px-4 py-2.5 rounded-xl mb-3">2. Kewajiban (Liabilities)</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse($liabilities as $liab)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-3 px-4 font-mono text-slate-500 w-28">{{ $liab->code }}</td>
                                        <td class="py-3 px-4">{{ $liab->name }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-medium">Rp {{ number_format($liab->amount, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-slate-400 text-sm">Tidak ada data kewajiban.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="font-bold text-slate-900 border-t border-slate-200 bg-slate-50/50">
                                    <td colspan="2" class="py-3 px-4 text-right uppercase text-xs">Total Kewajiban:</td>
                                    <td class="py-3 px-4 text-right font-mono text-indigo-600">Rp {{ number_format($totalLiability, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Ekuitas (Equity) -->
                <div>
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider bg-slate-50 px-4 py-2.5 rounded-xl mb-3">3. Ekuitas (Equity)</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse($equities as $eq)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-3 px-4 font-mono text-slate-500 w-28">{{ $eq->code }}</td>
                                        <td class="py-3 px-4">{{ $eq->name }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-medium">Rp {{ number_format($eq->amount, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-slate-400 text-sm">Tidak ada data ekuitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="font-bold text-slate-900 border-t border-slate-200 bg-slate-50/50">
                                    <td colspan="2" class="py-3 px-4 text-right uppercase text-xs">Total Ekuitas:</td>
                                    <td class="py-3 px-4 text-right font-mono text-indigo-600">Rp {{ number_format($totalEquity, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Total Kewajiban + Ekuitas -->
                <div class="p-4 rounded-xl bg-slate-900 text-white flex justify-between items-center text-sm font-bold">
                    <span class="uppercase tracking-wider text-xs">Total Kewajiban & Ekuitas:</span>
                    <span class="font-mono text-base">Rp {{ number_format($totalLiabilityAndEquity, 2, ',', '.') }}</span>
                </div>

            </div>

        </div>

        <!-- Indikator Keseimbangan Neraca -->
        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Validasi Neraca:</span>
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase
                @if($isBalanced) bg-emerald-50 text-emerald-700 border border-emerald-200
                @else bg-rose-50 text-rose-700 border border-rose-200 @endif">
                {{ $isBalanced ? 'Seimbang (Balanced)' : 'Tidak Seimbang (Unbalanced)' }}
            </span>
        </div>

    </div>

</div>
@endsection
