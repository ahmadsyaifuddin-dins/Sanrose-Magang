<x-app-layout>
    {{-- Slot untuk CSS tambahan di <head> --}}
    <x-slot name="styles">
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            #map { 
                height: 400px; 
                border-radius: 0.5rem;
            }
            
            /* Dark mode styles for Leaflet map */
            .dark #map {
                filter: brightness(0.8) contrast(1.2);
            }
            
            /* Leaflet popup dark mode */
            .dark .leaflet-popup-content-wrapper {
                background-color: #374151 !important;
                color: #f9fafb !important;
            }
            
            .dark .leaflet-popup-tip {
                background-color: #374151 !important;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Detail Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    {{-- Header Section with Back Button --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <a href="{{ route('instansi.list') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Kembali ke Daftar
                            </a>
                        </div>
                        
                        <h1 class="text-4xl font-bold mb-3 text-gray-900 dark:text-white">{{ $instansi->nama_instansi }}</h1>
                        <div class="flex items-start space-x-2 text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-lg">{{ $instansi->alamat }}</p>
                        </div>
                    </div>

                    {{-- Detail dan Peta (Layout Grid) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        {{-- Kolom Kiri: Detail Teks --}}
                        <div class="space-y-8">
                            {{-- Jadwal Kunjungan --}}
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-xl border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center mb-4">
                                    <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-xl text-gray-900 dark:text-white">Jadwal Kunjungan</h3>
                                </div>
                                <div class="space-y-3 text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <span class="font-semibold w-20 text-gray-600 dark:text-gray-400">Tanggal:</span>
                                        <span class="ml-2 text-lg">{{ \Carbon\Carbon::parse($instansi->waktu_kunjungan)->isoFormat('dddd, D MMMM Y') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="font-semibold w-20 text-gray-600 dark:text-gray-400">Jam:</span>
                                        <span class="ml-2 text-lg">{{ \Carbon\Carbon::parse($instansi->jam_kunjungan)->format('H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Daftar Pemilih --}}
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-6 rounded-xl border border-green-200 dark:border-green-800">
                                <div class="flex items-center mb-4">
                                    <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-xl text-gray-900 dark:text-white">
                                        Daftar Pemilih 
                                        <span class="text-sm font-normal text-gray-600 dark:text-gray-400">({{ $instansi->pilihan->count() }} orang)</span>
                                    </h3>
                                </div>
                                @if($instansi->pilihan->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($instansi->pilihan as $pilihan)
                                            <div class="flex items-center p-3 bg-white dark:bg-gray-700/50 rounded-lg border border-green-100 dark:border-green-800/50">
                                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $pilihan->user->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Belum ada yang memilih instansi ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Kanan: Peta --}}
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-6 rounded-xl border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-xl text-gray-900 dark:text-white">Lokasi Peta</h3>
                            </div>
                            
                            @if($instansi->latitude && $instansi->longitude)
                                <div class="relative">
                                    <div id="map" class="shadow-lg border border-gray-200 dark:border-gray-600"></div>
                                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center space-x-4">
                                        <span>📍 Lat: {{ $instansi->latitude }}</span>
                                        <span>📍 Lng: {{ $instansi->longitude }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="h-96 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <svg class="h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Koordinat lokasi tidak tersedia</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Silakan tambahkan koordinat untuk menampilkan peta</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Galeri Gambar --}}
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 p-6 rounded-xl border border-orange-200 dark:border-orange-800">
                        <div class="flex items-center mb-6">
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-xl text-gray-900 dark:text-white">
                                Galeri Lokasi 
                                <span class="text-sm font-normal text-gray-600 dark:text-gray-400">({{ $instansi->images->count() }} gambar)</span>
                            </h3>
                        </div>
                        
                        @if($instansi->images->isNotEmpty())
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($instansi->images as $img)
                                    <a href="{{ asset($img->path_gambar) }}" target="_blank" class="group relative block">
                                        <div class="relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                                            <img src="{{ asset($img->path_gambar) }}" 
                                                 alt="Gambar {{ $instansi->nama_instansi }}" 
                                                 class="w-full h-48 object-cover">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-2">Belum ada gambar</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Tidak ada gambar untuk instansi ini</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Slot untuk JS tambahan sebelum </body> --}}
    <x-slot name="scripts">
        @if($instansi->latitude && $instansi->longitude)
            <!-- Leaflet JS -->
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const lat = {{ $instansi->latitude }};
                    const lng = {{ $instansi->longitude }};

                    const map = L.map('map').setView([lat, lng], 15); // Zoom lebih dekat (15)

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    const marker = L.marker([lat, lng]).addTo(map);
                    marker.bindPopup("<b>{{ $instansi->nama_instansi }}</b><br>{{ $instansi->alamat }}").openPopup();
                });
            </script>
        @endif
    </x-slot>
</x-app-layout>