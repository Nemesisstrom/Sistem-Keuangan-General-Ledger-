<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Keuangan') | Arunika Finance</title>
    <meta name="theme-color" content="#10271f">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print, header, aside { display: none !important; }
            main { max-width: none !important; padding: 0 !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#f5f7f5] text-slate-800 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="no-print hidden w-64 shrink-0 flex-col bg-[#10271f] text-white lg:flex">
            <a href="{{ route('dashboard') }}" class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-400 text-lg font-black text-[#10271f]">A</span>
                <span>
                    <span class="block text-sm font-bold tracking-wide">ARUNIKA</span>
                    <span class="block text-[10px] uppercase tracking-[.2em] text-emerald-100/60">Finance & Ledger</span>
                </span>
            </a>

            <div class="px-4 py-6">
                <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[.18em] text-emerald-100/45">Workspace</p>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-emerald-50/65 hover:bg-white/5 hover:text-white' }}">
                        <span class="w-5 text-center">⌂</span> Ringkasan
                    </a>
                    <a href="{{ route('journals.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('journals.*') ? 'bg-white/10 text-white' : 'text-emerald-50/65 hover:bg-white/5 hover:text-white' }}">
                        <span class="w-5 text-center">▤</span> Jurnal Umum
                    </a>
                    @role('Admin')
                        <a href="{{ route('accounts.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('accounts.*') ? 'bg-white/10 text-white' : 'text-emerald-50/65 hover:bg-white/5 hover:text-white' }}">
                            <span class="w-5 text-center">▦</span> Daftar Akun
                        </a>
                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('users.*') ? 'bg-white/10 text-white' : 'text-emerald-50/65 hover:bg-white/5 hover:text-white' }}">
                            <span class="w-5 text-center">♙</span> Pengguna
                        </a>
                    @endrole
                </nav>

                <p class="mb-3 mt-8 px-3 text-[10px] font-bold uppercase tracking-[.18em] text-emerald-100/45">Laporan</p>
                <nav class="space-y-1">
                    <a href="{{ route('reports.general-ledger') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('reports.general-ledger') ? 'bg-white/10 text-white' : 'text-emerald-50/65 hover:bg-white/5 hover:text-white' }}">
                        <span class="w-5 text-center">≡</span> Buku Besar
                    </a>
                    <a href="{{ route('reports.profit-loss') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('reports.profit-loss') ? 'bg-white/10 text-white' : 'text-emerald-50/65 hover:bg-white/5 hover:text-white' }}">
                        <span class="w-5 text-center">↗</span> Laba Rugi
                    </a>
                    <a href="{{ route('reports.balance-sheet') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('reports.balance-sheet') ? 'bg-white/10 text-white' : 'text-emerald-50/65 hover:bg-white/5 hover:text-white' }}">
                        <span class="w-5 text-center">⚖</span> Neraca
                    </a>
                </nav>
            </div>

            <div class="mt-auto border-t border-white/10 p-4">
                <div class="mb-3 flex items-center gap-3 px-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-300/15 text-sm font-bold text-emerald-200">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                    <span class="min-w-0">
                        <span class="block truncate text-xs font-semibold">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                        <span class="block text-[10px] text-emerald-100/50">{{ auth()->user()->branch?->name ?? 'General Ledger' }}</span>
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full rounded-lg px-3 py-2 text-left text-xs text-emerald-50/65 hover:bg-white/5 hover:text-white">Keluar dari sistem</button>
                </form>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="no-print sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur">
                <div class="flex h-16 items-center justify-between px-4 sm:px-8">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#e9f3ec] font-bold text-[#174735] lg:hidden">A</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">@yield('page-title', 'Sistem Keuangan')</p>
                            <p class="hidden text-[11px] text-slate-400 sm:block">@yield('page-subtitle', 'General Ledger & Financial Reporting')</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @hasSection('page-actions')
                            <div class="mr-1">@yield('page-actions')</div>
                        @endif
                        <span class="hidden text-xs text-slate-500 sm:inline">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#e9f3ec] text-xs font-bold text-[#174735]">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                    </div>
                </div>
                <nav class="flex gap-1 overflow-x-auto border-t border-slate-100 px-4 py-2 text-xs lg:hidden">
                    <a class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-slate-100" href="{{ route('dashboard') }}">Ringkasan</a>
                    <a class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-slate-100" href="{{ route('journals.index') }}">Jurnal</a>
                    @role('Admin')
                        <a class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-slate-100" href="{{ route('accounts.index') }}">Daftar Akun</a>
                        <a class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-slate-100" href="{{ route('users.index') }}">Pengguna</a>
                    @endrole
                    <a class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-slate-100" href="{{ route('reports.general-ledger') }}">Buku Besar</a>
                    <a class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-slate-100" href="{{ route('reports.profit-loss') }}">Laba Rugi</a>
                    <a class="whitespace-nowrap rounded-lg px-3 py-2 hover:bg-slate-100" href="{{ route('reports.balance-sheet') }}">Neraca</a>
                    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                        @csrf
                        <button class="whitespace-nowrap rounded-lg px-3 py-2 text-rose-700 hover:bg-rose-50">Keluar</button>
                    </form>
                </nav>
            </header>

            <main class="mx-auto max-w-[1500px] px-4 py-6 sm:px-8 sm:py-8">
                @if(session('success'))
                    <div class="no-print mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <span class="font-bold">✓</span><span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="no-print mb-5 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        <span class="font-bold">!</span><span>{{ session('error') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
            <footer class="no-print px-4 pb-6 text-center text-[10px] text-slate-400 sm:px-8">ARUNIKA FINANCE <span class="mx-1">·</span> General Ledger System</footer>
        </div>
    </div>
    @livewireScripts
</body>
</html>
