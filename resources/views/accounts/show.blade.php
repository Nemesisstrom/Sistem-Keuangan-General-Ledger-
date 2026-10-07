@extends('layouts.app')
@section('title', $account->code . ' · ' . $account->name)
@section('page-title', 'Detail akun')
@section('page-subtitle', 'Informasi dan struktur akun yang dipilih.')
@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('accounts.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-800">← Daftar akun</a>
        <div class="flex gap-2">
            <a href="{{ route('accounts.edit', $account) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Ubah akun</a>
            <form method="POST" action="{{ route('accounts.destroy', $account) }}" onsubmit="return confirm('Hapus akun ini?')">@csrf @method('DELETE')<button class="rounded-xl border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button></form>
        </div>
    </div>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-[#10271f] to-[#246147] px-6 py-8 text-white sm:px-8">
            <p class="font-mono text-xs tracking-wider text-emerald-100/70">{{ $account->code }}</p>
            <h1 class="mt-2 text-2xl font-bold">{{ $account->name }}</h1>
            <span class="mt-4 inline-flex rounded-full bg-white/10 px-3 py-1 text-xs capitalize">{{ $account->type }}</span>
        </div>
        <dl class="grid divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
            <div class="p-6"><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Saldo normal</dt><dd class="mt-2 text-sm font-semibold capitalize text-slate-800">{{ $account->normal_balance }}</dd></div>
            <div class="p-6"><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</dt><dd class="mt-2 text-sm font-semibold {{ $account->is_active ? 'text-emerald-700' : 'text-slate-500' }}">{{ $account->is_active ? 'Aktif' : 'Nonaktif' }}</dd></div>
            <div class="p-6"><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Akun induk</dt><dd class="mt-2 text-sm text-slate-800">{{ $account->parent ? $account->parent->code . ' · ' . $account->parent->name : 'Tidak ada' }}</dd></div>
            <div class="p-6"><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Akun turunan</dt><dd class="mt-2 text-sm text-slate-800">{{ $account->children->count() }} akun</dd></div>
            <div class="p-6 sm:col-span-2"><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Baris transaksi tercatat</dt><dd class="mt-2 text-sm text-slate-800">{{ number_format($account->journal_items_count) }} baris jurnal</dd></div>
        </dl>
    </section>
    @if($account->children->isNotEmpty())
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800">Sub-akun</h2>
            <div class="mt-3 divide-y divide-slate-100">
                @foreach($account->children as $child)
                    <a href="{{ route('accounts.show', $child) }}" class="flex items-center justify-between py-3 text-sm hover:text-emerald-800"><span><span class="font-mono text-xs text-slate-400">{{ $child->code }}</span><span class="ml-3 font-medium">{{ $child->name }}</span></span><span class="text-slate-300">→</span></a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
