<x-app-layout>
    {{-- Slot untuk CSS tambahan di <head> --}}
    <x-slot name="styles">
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            #map { height: 65vh; } /* Menggunakan vh (viewport height) agar lebih responsif */
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peta Lokasi Instansi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="map" class="rounded-lg z-0"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slot untuk JS tambahan sebelum </body> --}}
    <x-slot name="scripts">
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Inisialisasi peta, berpusat di Indonesia
                const map = L.map('map').setView([-2.548926, 118.0148634], 5);

                // Tambahkan tile layer dari OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                // Ambil data lokasi dari PHP (Blade)
                const lokasi = @json($lokasiInstansi);
                const markers = [];

                // Buat marker untuk setiap lokasi
                lokasi.forEach(l => {
                    if (l.latitude && l.longitude) {
                        const marker = L.marker([l.latitude, l.longitude]).addTo(map);
                        marker.bindPopup(`<b>${l.nama_instansi}</b><br>${l.alamat}`);
                        markers.push(marker);
                    }
                });

                // Menyesuaikan zoom agar semua marker terlihat (jika ada marker)
                if (markers.length > 0) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.5));
                }
            });
        </script>
    </x-slot>
</x-app-layout>
