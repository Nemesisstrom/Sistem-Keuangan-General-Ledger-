@extends('layouts.app')

@section('title', 'Detail Akun: ' . $account->code . ' - SI Keuangan')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header Kembali & Aksi -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="font-mono bg-indigo-50 text-indigo-700 px-3 py-1 rounded-xl text-sm font-bold">{{ $account->code }}</span>
                <h1 class="text-2xl font-bold text-slate-800">{{ $account->name }}</h1>
            </div>
            <p class="text-sm text-slate-500 mt-1">Informasi detail akun buku besar dan relasinya.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('accounts.edit', $account->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Akun
            </a>
            <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-indigo-600 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm transition-all">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    <!-- Card Informasi Utama -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6 p-6 sm:p-8">
        <h2 class="text-base font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100">Informasi Dasar Akun</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kode Akun</p>
                <p class="font-mono font-medium text-slate-900 mt-1 text-base">{{ $account->code }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Akun</p>
                <p class="font-medium text-slate-900 mt-1 text-base">{{ $account->name }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tipe Akun (Type)</p>
                <p class="mt-1">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium uppercase
                        @if($account->type == 'asset') bg-blue-50 text-blue-700
                        @elseif($account->type == 'liability') bg-amber-50 text-amber-700
                        @elseif($account->type == 'equity') bg-purple-50 text-purple-700
                        @elseif($account->type == 'revenue') bg-emerald-50 text-emerald-700
                        @else bg-rose-50 text-rose-700 @endif">
                        {{ $account->type }}
                    </span>
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Saldo Normal (Normal Balance)</p>
                <p class="font-medium text-slate-900 mt-1 uppercase text-xs">{{ $account->normal_balance }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Akun Induk (Parent Account)</p>
                <p class="font-medium text-slate-900 mt-1">
                    @if($account->parent)
                        <a href="{{ route('accounts.show', $account->parent->id) }}" class="text-indigo-600 hover:underline">
                            {{ $account->parent->code }} - {{ $account->parent->name }}
                        </a>
                    @else
                        <span class="text-slate-400">Akun Utama (Tidak memiliki induk)</span>
                    @endif
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Status Akun</p>
                <p class="mt-1">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $account->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                        {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jumlah Transaksi Terkait</p>
                <p class="font-medium text-slate-900 mt-1">{{ $account->journal_items_count ?? 0 }} baris jurnal</p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Deskripsi / Keterangan</p>
                <p class="text-slate-600 mt-1 bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $account->description ?? 'Tidak ada keterangan tambahan.' }}</p>
            </div>
        </div>
    </div>

    <!-- Card Daftar Sub-Akun (Children) jika ada -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="font-bold text-slate-800 text-base">Daftar Sub-Akun (Children)</h2>
            <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2.5 py-1 rounded-full">{{ $account->children->count() }} Sub-akun</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-xs border-b border-slate-100">
                        <th class="py-3 px-6 font-semibold">Kode Akun</th>
                        <th class="py-3 px-6 font-semibold">Nama Sub-Akun</th>
                        <th class="py-3 px-6 font-semibold">Saldo Normal</th>
                        <th class="py-3 px-6 font-semibold text-center">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($account->children as $child)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-mono font-medium text-slate-900">{{ $child->code }}</td>
                            <td class="py-4 px-6 font-medium text-slate-900">{{ $child->name }}</td>
                            <td class="py-4 px-6 uppercase text-xs text-slate-500">{{ $child->normal_balance }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $child->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('accounts.show', $child->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs bg-indigo-50 px-3 py-1 rounded-lg">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 text-sm">
                                Akun ini tidak memiliki sub-akun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tombol Hapus Akun -->
    <div class="flex justify-end">
        <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-medium text-sm rounded-xl border border-red-200 transition-all">
                Hapus Akun Ini
            </button>
        </form>
    </div>

</div>
@endsection
