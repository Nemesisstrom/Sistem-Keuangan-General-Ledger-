@extends('layouts.app')

@section('title', 'Master Chart of Accounts - SI Keuangan')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Chart of Accounts (COA)</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola struktur akun buku besar dan hierarki perusahaan.</p>
        </div>
        <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Akun Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    <!-- Form Filter & Pencarian -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form action="{{ route('accounts.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Cari Kode / Nama Akun</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: 1-1100 atau Kas..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Filter Tipe Akun</label>
                <select name="type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="">-- Semua Tipe --</option>
                    <option value="asset" {{ request('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                    <option value="liability" {{ request('type') == 'liability' ? 'selected' : '' }}>Liability</option>
                    <option value="equity" {{ request('type') == 'equity' ? 'selected' : '' }}>Equity</option>
                    <option value="revenue" {{ request('type') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-md transition-all">Filter</button>
                <a href="{{ route('accounts.index') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium text-sm rounded-xl transition-all">Reset</a>
            </div>
        </form>
    </div>

    <!-- Tabel Data Akun -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-xs border-b border-slate-100">
                        <th class="py-3 px-6 font-semibold">Kode & Nama Akun</th>
                        <th class="py-3 px-6 font-semibold">Tipe Akun</th>
                        <th class="py-3 px-6 font-semibold">Saldo Normal</th>
                        <th class="py-3 px-6 font-semibold text-center">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($accounts as $account)
                        <!-- Jika Mode Filter Aktif (Flat List) -->
                        @if($isFiltered)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 flex items-center gap-2">
                                    <span class="font-mono font-medium text-slate-900">{{ $account->code }}</span>
                                    <a href="{{ route('accounts.show', $account->id) }}" class="text-slate-800 hover:text-indigo-600 hover:underline">{{ $account->name }}</a>
                                </td>
                                <td class="py-4 px-6 uppercase text-xs">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($account->type == 'asset') bg-blue-50 text-blue-700
                                        @elseif($account->type == 'liability') bg-amber-50 text-amber-700
                                        @elseif($account->type == 'equity') bg-purple-50 text-purple-700
                                        @elseif($account->type == 'revenue') bg-emerald-50 text-emerald-700
                                        @else bg-rose-50 text-rose-700 @endif">
                                        {{ $account->type }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 uppercase text-xs text-slate-500">{{ $account->normal_balance }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $account->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('accounts.show', $account->id) }}" class="text-slate-600 hover:text-slate-900 text-xs bg-slate-100 px-2.5 py-1 rounded-lg">Detail</a>
                                    <a href="{{ route('accounts.edit', $account->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs bg-indigo-50 px-2.5 py-1 rounded-lg">Edit</a>
                                </td>
                            </tr>
                        @else
                            <!-- Jika Mode Normal (Hierarki Bertingkat / Tree View) -->
                            <!-- Level 1: Akun Induk (Parent) -->
                            <tr class="hover:bg-slate-50/50 transition-colors font-semibold bg-slate-50/30">
                                <td class="py-4 px-6 flex items-center gap-2">
                                    <span class="font-mono text-indigo-600">{{ $account->code }}</span>
                                    <a href="{{ route('accounts.show', $account->id) }}" class="text-slate-900 hover:text-indigo-600 hover:underline">{{ $account->name }}</a>
                                </td>
                                <td class="py-4 px-6 uppercase text-xs">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $account->type }}</span>
                                </td>
                                <td class="py-4 px-6 uppercase text-xs text-slate-500">{{ $account->normal_balance }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $account->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('accounts.show', $account->id) }}" class="text-slate-600 hover:text-slate-900 text-xs bg-slate-100 px-2.5 py-1 rounded-lg">Detail</a>
                                    <a href="{{ route('accounts.edit', $account->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs bg-indigo-50 px-2.5 py-1 rounded-lg">Edit</a>
                                </td>
                            </tr>

                            <!-- Level 2: Sub-Akun (Children) -->
                            @foreach($account->children as $child)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3.5 px-6 pl-12 flex items-center gap-2">
                                        <span class="text-slate-300">└─</span>
                                        <span class="font-mono font-medium text-slate-700">{{ $child->code }}</span>
                                        <a href="{{ route('accounts.show', $child->id) }}" class="text-slate-800 hover:text-indigo-600 hover:underline">{{ $child->name }}</a>
                                    </td>
                                    <td class="py-3.5 px-6 uppercase text-xs">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($child->type == 'asset') bg-blue-50 text-blue-700
                                            @elseif($child->type == 'liability') bg-amber-50 text-amber-700
                                            @elseif($child->type == 'equity') bg-purple-50 text-purple-700
                                            @elseif($child->type == 'revenue') bg-emerald-50 text-emerald-700
                                            @else bg-rose-50 text-rose-700 @endif">
                                            {{ $child->type }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 uppercase text-xs text-slate-500">{{ $child->normal_balance }}</td>
                                    <td class="py-3.5 px-6 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-xs {{ $child->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-right space-x-2">
                                        <a href="{{ route('accounts.show', $child->id) }}" class="text-slate-600 hover:text-slate-900 text-xs bg-slate-100 px-2.5 py-1 rounded-lg">Detail</a>
                                        <a href="{{ route('accounts.edit', $child->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs bg-indigo-50 px-2.5 py-1 rounded-lg">Edit</a>
                                    </td>
                                </tr>

                                <!-- Level 3: Sub-Cucu (Grandchildren) -->
                                @foreach($child->children as $grandchild)
                                    <tr class="hover:bg-slate-50/50 transition-colors text-slate-600 text-xs">
                                        <td class="py-3 px-6 pl-20 flex items-center gap-2">
                                            <span class="text-slate-300">└─</span>
                                            <span class="font-mono font-medium text-slate-600">{{ $grandchild->code }}</span>
                                            <a href="{{ route('accounts.show', $grandchild->id) }}" class="text-slate-700 hover:text-indigo-600 hover:underline">{{ $grandchild->name }}</a>
                                        </td>
                                        <td class="py-3 px-6 uppercase">{{ $grandchild->type }}</td>
                                        <td class="py-3 px-6 uppercase">{{ $grandchild->normal_balance }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <span>{{ $grandchild->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-right space-x-2">
                                            <a href="{{ route('accounts.show', $grandchild->id) }}" class="text-slate-600 hover:text-slate-900 bg-slate-100 px-2 py-0.5 rounded">Detail</a>
                                            <a href="{{ route('accounts.edit', $grandchild->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-0.5 rounded">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">
                                Tidak ada data Chart of Accounts yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (Hanya muncul saat mode filter aktif) -->
        @if($isFiltered)
            <div class="p-4 border-t border-slate-100">
                {{ $accounts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
