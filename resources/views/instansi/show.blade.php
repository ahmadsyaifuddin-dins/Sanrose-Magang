<x-app-layout>
    {{-- Slot untuk CSS tambahan di <head> --}}
    <x-slot name="styles">
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            #map { height: 400px; }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Instansi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- Judul dan Alamat --}}
                    <h1 class="text-3xl font-bold mb-2">{{ $instansi->nama_instansi }}</h1>
                    <p class="text-gray-600 mb-4">{{ $instansi->alamat }}</p>
                    <a href="{{ route('instansi.list') }}" class="text-indigo-600 hover:text-indigo-800 text-sm mb-6 inline-block">&larr; Kembali ke Daftar Instansi</a>

                    {{-- Detail dan Peta (Layout Grid) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        {{-- Kolom Kiri: Detail Teks --}}
                        <div class="space-y-6">
                            <div>
                                <h3 class="font-semibold text-lg border-b pb-2 mb-3">Jadwal Kunjungan</h3>
                                <p><strong class="w-24 inline-block">Tanggal</strong>: {{ \Carbon\Carbon::parse($instansi->waktu_kunjungan)->isoFormat('dddd, D MMMM Y') }}</p>
                                <p><strong class="w-24 inline-block">Jam</strong>: {{ \Carbon\Carbon::parse($instansi->jam_kunjungan)->format('H:i') }} WIB</p>
                            </div>
                            
                            <div>
                                <h3 class="font-semibold text-lg border-b pb-2 mb-3">Daftar Pemilih</h3>
                                @if($instansi->pilihan->count() > 0)
                                    <ul class="list-disc list-inside text-gray-700">
                                        @foreach($instansi->pilihan as $pilihan)
                                            <li>{{ $pilihan->user->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">Belum ada yang memilih instansi ini.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Kanan: Peta --}}
                        <div>
                            @if($instansi->latitude && $instansi->longitude)
                                <div id="map" class="rounded-lg z-0"></div>
                            @else
                                <div class="h-full flex items-center justify-center bg-gray-100 rounded-lg">
                                    <p class="text-gray-500">Lokasi peta tidak tersedia.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Galeri Gambar --}}
                    <div>
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Galeri Lokasi</h3>
                        @if($instansi->images->isNotEmpty())
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($instansi->images as $img)
                                    <a href="{{ asset($img->path_gambar) }}" target="_blank">
                                        <img src="{{ asset($img->path_gambar) }}" alt="Gambar {{ $instansi->nama_instansi }}" class="w-full h-40 object-cover rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Tidak ada gambar untuk instansi ini.</p>
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
                    marker.bindPopup("<b>{{ $instansi->nama_instansi }}</b>").openPopup();
                });
            </script>
        @endif
    </x-slot>
</x-app-layout>
