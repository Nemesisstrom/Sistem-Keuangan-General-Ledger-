@extends('layouts.app')

@section('title', 'Jurnal Penyesuaian - SI Keuangan')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header & Tombol Aksi -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Jurnal Penyesuaian (Adjustments)</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola pencatatan penyesuaian akhir periode seperti depresiasi dan akrual.</p>
        </div>
        <button onclick="document.getElementById('modalDepreciation').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Proses Penyesuaian Penyusutan
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Daftar Jurnal Penyesuaian -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Riwayat Jurnal Penyesuaian Tercatat</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-xs border-b border-slate-100">
                        <th class="py-3 px-6 font-semibold">Tanggal</th>
                        <th class="py-3 px-6 font-semibold">No. Ref</th>
                        <th class="py-3 px-6 font-semibold">Keterangan Penyesuaian</th>
                        <th class="py-3 px-6 font-semibold">Cabang</th>
                        <th class="py-3 px-6 font-semibold text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($adjustments as $adj)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">{{ $adj->date }}</td>
                            <td class="py-4 px-6 font-mono font-medium text-slate-900">{{ $adj->reference_number }}</td>
                            <td class="py-4 px-6 font-medium text-slate-900">{{ $adj->description }}</td>
                            <td class="py-4 px-6 whitespace-nowrap">{{ $adj->branch->name ?? 'Pusat' }}</td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium uppercase bg-emerald-50 text-emerald-700">
                                    {{ $adj->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">Belum ada data jurnal penyesuaian yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $adjustments->links() }}
        </div>
    </div>

    <!-- MODAL FORM PENYESUAIAN PENYUSUTAN -->
    <div id="modalDepreciation" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 sm:p-8 space-y-6">

            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Form Penyesuaian Penyusutan Aset</h3>
                <button type="button" onclick="document.getElementById('modalDepreciation').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('adjustments.depreciation') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Penyesuaian</label>
                    <input type="date" name="date" value="{{ date('Y-m-t') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Akun Beban Penyusutan (Debit)</label>
                    <select name="expense_account_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                        <option value="">-- Pilih Akun Beban --</option>
                        @foreach($accounts as $acc)
                            @if($acc->type == 'expense')
                                <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Akun Akumulasi Penyusutan (Kredit)</label>
                    <select name="asset_account_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                        <option value="">-- Pilih Akun Akumulasi/Aset --</option>
                        @foreach($accounts as $acc)
                            @if($acc->type == 'asset')
                                <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nominal Penyusutan (Rp)</label>
                    <input type="number" step="0.01" name="amount" placeholder="0.00" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Keterangan / Memo</label>
                    <textarea name="description" rows="2" placeholder="Contoh: Penyusutan Inventaris Kantor Bulan Ini" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalDepreciation').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-md transition-all">Simpan & Post</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
