@extends('layouts.app')

@section('title', 'Jurnal Umum')
@section('page-title', 'Jurnal Umum')
@section('page-subtitle', 'Pantau dan telusuri pencatatan transaksi keuangan.')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-700">General ledger</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Jurnal transaksi</h1>
            <p class="mt-1 text-sm text-slate-500">Seluruh entri jurnal yang telah dicatat ke buku besar.</p>
        </div>
        <a href="{{ route('journals.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#174735] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#103626]"><span class="text-lg leading-none">+</span> Entri jurnal</a>
    </div>

    <div class="grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jumlah entri</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($journals->total()) }}</p><p class="mt-1 text-xs text-slate-400">Sesuai filter aktif</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Periode</p><p class="mt-2 text-base font-bold text-slate-900">{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Semua tanggal' }}</p><p class="mt-1 text-xs text-slate-400">{{ request('end_date') ? 's.d. ' . \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Tanpa batas tanggal akhir' }}</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status filter</p><p class="mt-2 text-base font-bold capitalize text-slate-900">{{ request('status', 'Semua status') }}</p><p class="mt-1 text-xs text-slate-400">Jurnal tersimpan dalam buku besar</p></div>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('journals.index') }}" class="grid gap-3 border-b border-slate-100 bg-white p-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2"><label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Cari jurnal</label><input name="search" value="{{ request('search') }}" placeholder="Nomor jurnal atau keterangan" class="w-full rounded-lg border-slate-200 text-sm focus:border-emerald-700 focus:ring-emerald-700"></div>
            <div><label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Dari tanggal</label><input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-lg border-slate-200 text-sm focus:border-emerald-700 focus:ring-emerald-700"></div>
            <div><label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Sampai tanggal</label><input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-lg border-slate-200 text-sm focus:border-emerald-700 focus:ring-emerald-700"></div>
            <div><label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</label><select name="status" class="w-full rounded-lg border-slate-200 text-sm focus:border-emerald-700 focus:ring-emerald-700"><option value="">Semua status</option><option value="posted" @selected(request('status') === 'posted')>Posted</option><option value="draft" @selected(request('status') === 'draft')>Draft</option></select></div>
            <div class="flex gap-2 sm:col-span-2 lg:col-span-5 lg:justify-end"><a href="{{ route('journals.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Reset</a><button class="rounded-lg bg-[#174735] px-5 py-2 text-xs font-semibold text-white hover:bg-[#103626]">Terapkan filter</button></div>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-400"><tr><th class="px-5 py-3">Tanggal / nomor jurnal</th><th class="px-5 py-3">Keterangan</th><th class="px-5 py-3">Cabang</th><th class="px-5 py-3 text-right">Total debit</th><th class="px-5 py-3 text-center">Status</th><th class="px-5 py-3 text-right">Detail</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($journals as $journal)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-5 py-4"><p class="font-semibold text-slate-800">{{ $journal->date?->format('d M Y') }}</p><p class="mt-1 font-mono text-[10px] text-slate-400">{{ $journal->entry_number }}</p></td>
                            <td class="max-w-sm px-5 py-4"><p class="truncate font-medium text-slate-700">{{ $journal->description }}</p><p class="mt-1 text-[10px] text-slate-400">{{ $journal->items->count() }} baris akun</p></td>
                            <td class="px-5 py-4 text-xs text-slate-600">{{ $journal->branch?->code ?? '—' }}</td>
                            <td class="px-5 py-4 text-right font-semibold tabular-nums text-slate-800">Rp {{ number_format((float) $journal->items->sum('debit'), 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center"><span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase {{ $journal->status === 'posted' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $journal->status }}</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('journals.show', $journal) }}" class="text-xs font-semibold text-emerald-800 hover:underline">Buka →</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-16 text-center"><span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400">▤</span><p class="mt-3 text-sm font-semibold text-slate-700">Belum ada jurnal yang cocok</p><p class="mt-1 text-xs text-slate-400">Ubah filter atau buat entri jurnal baru.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($journals->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $journals->links() }}</div>@endif
    </section>
</div>
@endsection
