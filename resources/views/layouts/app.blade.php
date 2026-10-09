<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Informasi Keuangan') }}</title>

    <!-- Tailwind CSS (via CDN jika belum setup Vite/Mix) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js (opsional untuk interaksi dropdown/navbar) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col">

    <!-- Navigation Header -->
    <nav class="bg-indigo-600 text-white shadow-md print:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="font-bold text-lg tracking-wider">
                        SI KEUANGAN
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-4 text-sm font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">Dashboard</a>
                    <a href="{{ route('journals.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">Jurnal Umum</a>
                    <a href="{{ route('reports.general-ledger') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">Buku Besar</a>
                    <a href="{{ route('reports.profit-loss') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">Laba Rugi</a>
                    <a href="{{ route('reports.balance-sheet') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">Neraca</a>
                </div>

                <!-- User Info & Logout -->
                <div class="flex items-center gap-4">
                    @auth
                        <span class="text-xs bg-indigo-700 px-3 py-1 rounded-full">
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md font-semibold">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

        <!-- Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-xs text-gray-500 print:hidden">
        &copy; {{ date('Y') }} Sistem Informasi Keuangan Multi-Cabang.
    </footer>

</body>
</html>
