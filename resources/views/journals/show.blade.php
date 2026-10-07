@extends('layouts.app')
@section('title', 'Detail ' . $journal->entry_number)
@section('page-title', 'Detail jurnal')
@section('page-subtitle', 'Tinjau transaksi dan rincian akun yang dibukukan.')
@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <div class="no-print flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('journals.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-800">← Daftar jurnal</a>
        <button onclick="window.print()" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Cetak / PDF</button>
    </div>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-5 bg-gradient-to-r from-[#10271f] to-[#246147] px-6 py-7 text-white sm:flex-row sm:items-end sm:px-8">
            <div><p class="text-[10px] font-bold uppercase tracking-[.2em] text-emerald-100/60">Bukti jurnal</p><h1 class="mt-2 font-mono text-xl font-bold">{{ $journal->entry_number }}</h1><p class="mt-2 text-sm text-emerald-50/80">{{ $journal->description }}</p></div>
            <span class="w-fit rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider">{{ $journal->status }}</span>
        </div>
        <dl class="grid gap-4 border-b border-slate-100 bg-slate-50/70 px-6 py-5 sm:grid-cols-3 sm:px-8">
            <div><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal transaksi</dt><dd class="mt-1.5 text-sm font-semibold text-slate-800">{{ $journal->date?->locale('id')->translatedFormat('d F Y') }}</dd></div>
            <div><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Cabang</dt><dd class="mt-1.5 text-sm font-semibold text-slate-800">{{ $journal->branch?->code }} · {{ $journal->branch?->name }}</dd></div>
            <div><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Dibuat oleh</dt><dd class="mt-1.5 text-sm font-semibold text-slate-800">{{ $journal->creator?->name ?? 'Sistem' }}</dd></div>
        </dl>
        <div class="overflow-x-auto p-4 sm:p-8">
            <table class="w-full min-w-[650px] text-left text-sm">
                <thead class="border-b border-slate-200 text-[10px] uppercase tracking-wider text-slate-400"><tr><th class="py-3">Akun</th><th class="py-3">Keterangan</th><th class="py-3 text-right">Debit</th><th class="py-3 text-right">Kredit</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($journal->items as $item)
                        <tr><td class="py-4"><span class="font-mono text-[10px] text-slate-400">{{ $item->account?->code }}</span><span class="ml-3 font-semibold text-slate-800">{{ $item->account?->name }}</span></td><td class="py-4 text-xs text-slate-500">{{ $item->description ?: '—' }}</td><td class="py-4 text-right tabular-nums">{{ (float) $item->debit > 0 ? 'Rp ' . number_format((float) $item->debit, 2, ',', '.') : '—' }}</td><td class="py-4 text-right tabular-nums">{{ (float) $item->credit > 0 ? 'Rp ' . number_format((float) $item->credit, 2, ',', '.') : '—' }}</td></tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-200 text-sm font-bold text-slate-900">
                    <tr><td class="py-4" colspan="2">TOTAL</td><td class="py-4 text-right tabular-nums">Rp {{ number_format((float) $journal->items->sum('debit'), 2, ',', '.') }}</td><td class="py-4 text-right tabular-nums">Rp {{ number_format((float) $journal->items->sum('credit'), 2, ',', '.') }}</td></tr>
                </tfoot>
            </table>
        </div>
    </section>
</div>
@endsection
