<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sanrose Magang - Manajemen Kunjungan Instansi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans">
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-indigo-500 selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex flex-col items-center lg:justify-center lg:col-start-2">
                        <img src="{{ asset('app-icon.png') }}" alt="Logo" class="w-32 mt-4">
                    </div>
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @if (Route::has('login'))
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </header>

                <main class="mt-20">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">
                            <span class="text-indigo-600 font-bold dark:text-indigo-700" style="text-shadow: 0 0 18px #6366f1">Sanrose Magang</span> adalah Aplikasi untuk Membantu Kunjungan Instansi Menjadi Mudah
                        </h1>
                        <p class="mt-6 max-w-xl mx-auto text-lg text-gray-600 dark:text-gray-400">
                            Aplikasi Sanrose Magang membantu para peserta magang untuk memilih lokasi kunjungan dengan mudah, melihat detail lokasi di peta interaktif, dan mencari rute terbaik.
                        </p>
                        <div class="mt-8">
                            <a href="{{ route('login') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                Masuk ke Aplikasi
                            </a>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black/50 dark:text-white/50">
                    Copyright &copy; {{ date('Y') }} Sanrose Magang - Dev by <a href="https://github.com/ahmadsyaifuddin-dins" target="_blank" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Ahmad Syaifuddin</a> 
                </footer>
            </div>
        </div>
    </div>
</body>
</html>
