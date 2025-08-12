<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KARTU SELAMAT DATANG KUSTOM ANDA -->
            <div class="mb-8">
                <div class="bg-gradient-to-br from-white via-gray-50 to-gray-200 dark:from-gray-800 dark:via-gray-700 dark:to-gray-900 rounded-2xl shadow-lg">
                    <div class="p-6 text-center">
                        <p class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">Halo, {{ auth()->user()->name }}!</p>
                        <p class="text-gray-600 dark:text-gray-300">Selamat datang di <span class="font-bold text-indigo-600 dark:text-indigo-400" style="text-shadow: 0 0 8px #6366f1, 0 0 16px #6366f1;">Sanrose Magang</span></p>
                    </div>
                </div>
            </div>
            <!-- AKHIR KARTU SELAMAT DATANG -->

            <!-- Kartu Statistik Umum -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Instansi -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Instansi</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $totalInstansi }}</p>
                </div>
                <!-- Total Peserta Magang -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Peserta Magang</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $totalMaganger }}</p>
                </div>
                <!-- Sudah Memilih -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sudah Memilih</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $totalPilihan }} / {{ $totalMaganger }}</p>
                </div>
            </div>

            <!-- Panel Spesifik Role -->
            @if(auth()->user()->role == 'maganger')
                {{-- Tampilan untuk role maganger --}}
                @if($pilihanUser)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pilihan Anda</h3>
                            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <p class="text-gray-600 dark:text-gray-400">Anda telah memilih:</p>
                                <h4 class="text-xl font-semibold text-indigo-600 dark:text-indigo-400 mt-1">{{ $pilihanUser->instansi->nama_instansi }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $pilihanUser->instansi->alamat }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jadwal: {{ \Carbon\Carbon::parse($pilihanUser->instansi->waktu_kunjungan)->isoFormat('dddd, D MMMM Y') }}, Pukul {{ \Carbon\Carbon::parse($pilihanUser->instansi->jam_kunjungan)->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 dark:bg-blue-900/50 border-l-4 border-blue-500 text-blue-800 dark:text-blue-200 p-4 rounded-r-lg">
                        <p class="font-bold">Anda Belum Memilih Instansi</p>
                        <p class="text-sm mt-1">Silakan pilih satu instansi tujuan kunjungan Anda. Pilihan bersifat final dan tidak dapat diubah.</p>
                        <a href="{{ route('instansi.list') }}" class="inline-block mt-3 px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                            Lihat Daftar Instansi
                        </a>
                    </div>
                @endif
            @elseif(auth()->user()->role == 'superadmin')
                {{-- Tampilan untuk role superadmin dengan layout grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kolom Kiri: Rekapitulasi Pilihan -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Rekapitulasi Pilihan</h3>
                            <div class="mt-4 space-y-4">
                                @forelse($instansiDenganPilihan as $instansi)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $instansi->nama_instansi }} (Dipilih oleh {{ $instansi->pilihan->count() }} orang)</p>
                                        <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 mt-2">
                                            @foreach($instansi->pilihan as $pilihan)
                                                <li>{{ $pilihan->user->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">Belum ada peserta yang memilih instansi.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Aktivitas Login Terbaru -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aktivitas Login Terbaru</h3>
                            <div class="mt-4 flow-root">
                                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($recentLogins as $user)
                                        <li class="py-3 sm:py-4">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500 dark:bg-gray-600">
                                                        <span class="text-sm font-medium leading-none text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                                </div>
                                                <div class="inline-flex flex-col items-end text-sm text-gray-600 dark:text-gray-400">
                                                    @if($user->last_login_at)
                                                        <span class="font-medium">{{ $user->last_login_at->diffForHumans() }}</span>
                                                        <span class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $user->last_login_at->isoFormat('D MMM Y, HH:mm') }}</span>
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data login.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
