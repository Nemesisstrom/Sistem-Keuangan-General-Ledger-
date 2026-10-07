@extends('layouts.app')
@section('title', 'Ubah Akun')
@section('page-title', 'Ubah akun')
@section('page-subtitle', 'Perbarui informasi akun {{ $account->code }}.')
@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('accounts.show', $account) }}" class="mb-5 inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-emerald-800">← Kembali ke detail akun</a>
    <form method="POST" action="{{ route('accounts.update', $account) }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @csrf @method('PUT')
        <div class="border-b border-slate-100 px-6 py-5"><h2 class="text-base font-bold text-slate-800">Informasi akun</h2><p class="mt-1 text-xs text-slate-400">Kode akun harus tetap unik di seluruh bagan akun.</p></div>
        <div class="p-6">@include('accounts._form')</div>
        <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4"><a href="{{ route('accounts.show', $account) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a><button class="rounded-xl bg-[#174735] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#103626]">Simpan perubahan</button></div>
    </form>
</div>
@endsection
