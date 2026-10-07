<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Akses akun') | Arunika Finance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-[#f5f7f5] p-4 sm:p-8">
    <main class="grid w-full max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5 lg:min-h-[620px] lg:grid-cols-2">
        <section class="relative hidden flex-col justify-between overflow-hidden bg-[#10271f] p-10 text-white lg:flex">
            <div class="absolute -right-20 -top-24 h-80 w-80 rounded-full border border-emerald-200/10"></div>
            <div class="absolute -right-8 -top-12 h-56 w-56 rounded-full border border-emerald-200/10"></div>
            <div class="relative">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-300 text-lg font-black text-[#10271f]">A</span>
                    <span><span class="block text-sm font-bold tracking-[.12em]">ARUNIKA</span><span class="block text-[10px] uppercase tracking-[.2em] text-emerald-100/55">Finance & Ledger</span></span>
                </a>
            </div>
            <div class="relative max-w-md">
                <span class="mb-5 inline-flex rounded-full border border-emerald-100/15 bg-white/5 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[.18em] text-emerald-100/75">Financial control workspace</span>
                <h1 class="text-4xl font-semibold leading-tight tracking-tight">Keputusan keuangan yang lebih <span class="text-emerald-300">terarah.</span></h1>
                <p class="mt-5 text-sm leading-6 text-emerald-50/65">Pencatatan, pemantauan, dan pelaporan keuangan perusahaan dalam satu ruang kerja yang terintegrasi.</p>
                <div class="mt-10 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4"><p class="text-lg font-semibold">Terstruktur</p><p class="mt-1 text-[10px] text-emerald-50/50">Pencatatan berbasis akun</p></div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4"><p class="text-lg font-semibold">Terukur</p><p class="mt-1 text-[10px] text-emerald-50/50">Laporan dalam satu sistem</p></div>
                </div>
            </div>
            <p class="relative text-[10px] tracking-wide text-emerald-100/35">ARUNIKA FINANCE · GENERAL LEDGER</p>
        </section>
        <section class="flex items-center justify-center p-2 sm:p-6 lg:p-10">
            <div class="w-full max-w-md">@yield('content')</div>
        </section>
    </main>
</body>
</html>
