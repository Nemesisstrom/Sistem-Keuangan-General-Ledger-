<!-- Navigation Links -->
<nav class="bg-indigo-600 text-white p-4 flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <a href="{{ route('dashboard') }}" class="font-bold text-lg">Sistem Keuangan</a>

    <<!-- MENU UNTUK STAFF & ADMIN -->
        @can('create-journal')
            <a href="{{ route('cash.in') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md text-sm">Pemasukan Kas</a>
            <a href="{{ route('cash.out') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md text-sm">Pengeluaran Kas</a>
            <a href="{{ route('journals.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md text-sm">Jurnal Umum</a>
        @endcan

        @can('view-reports')
            <a href="{{ route('reports.general-ledger') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md text-sm">Buku Besar</a>
        @endcan

    <!-- MENU KHUSUS ADMIN -->
        @role('Admin')
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="hover:bg-indigo-700 px-3 py-2 rounded-md text-sm flex items-center gap-1">
                    <span>Kelola Master Data</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-lg py-1 z-50">
                    <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Kelola Pengguna</a>
                    <a href="{{ route('accounts.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Master COA</a>
                    <a href="{{ route('reconciliations.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Rekonsiliasi Bank</a>
                    <a href="{{ route('adjustments.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Jurnal Penyesuaian</a>
                </div>
            </div>
        @endrole
    </div>

    <!-- User Profile Dropdown -->
    <div class="text-sm font-medium">
        {{ auth()->user()->name }}
        <span class="bg-indigo-800 px-2 py-1 rounded text-xs ml-2">
            {{ auth()->user()->getRoleNames()->first() }}
        </span>
    </div>
</nav>
