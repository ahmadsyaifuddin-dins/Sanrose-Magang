<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Peta Lokasi Instansi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="map" class="border border-gray-200 dark:border-gray-700" style="height: 70vh; width: 100%; min-height: 500px; position: relative; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- LEAFLET CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        /* Override dasar */
        #map { z-index: 1 !important; }
        .leaflet-container { font-family: inherit !important; }
        .leaflet-tile { max-width: none !important; }
        #map * { box-sizing: content-box !important; }
        #map img { max-width: none !important; height: auto !important; }

        /* --- DARK MODE STYLES UNTUK LEAFLET --- */

        /* Latar belakang tile peta saat loading di dark mode */
        .dark .leaflet-tile-container {
             filter: brightness(0.6) invert(1) contrast(3) hue-rotate(200deg) saturate(0.3) brightness(0.7);
        }

        /* Kontrol Layer */
        .dark .leaflet-control-layers {
            background: rgb(31 41 55) !important; /* gray-800 */
            color: rgb(229 231 235) !important; /* gray-200 */
            border: 1px solid rgb(55 65 81) !important; /* gray-700 */
        }

        /* Popup */
        .dark .leaflet-popup-content-wrapper {
            background: rgb(31 41 55) !important; /* gray-800 */
            color: rgb(229 231 235) !important; /* gray-200 */
            border: 1px solid rgb(55 65 81) !important; /* gray-700 */
        }
        .dark .leaflet-popup-tip {
            background: rgb(31 41 55) !important; /* gray-800 */
        }
        .dark .leaflet-popup-close-button {
            color: rgb(229 231 235) !important; /* gray-200 */
        }
        .dark .leaflet-popup-close-button:hover {
            color: white !important;
        }

        /* Attribution */
        .dark .leaflet-control-attribution {
            background: rgba(31, 41, 55, 0.8) !important;
            color: #bbb !important;
        }
    </style>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => initMap(), 500);
    });
    window.addEventListener('load', function() {
        setTimeout(() => { if (!window.mapReady) initMap(); }, 1000);
    });

    function initMap() {
        try {
            if (window.mapReady) return;
            const mapContainer = document.getElementById('map');
            if (!mapContainer) return;
            if (mapContainer._leaflet_id) delete mapContainer._leaflet_id;
            mapContainer.innerHTML = '';
            
            // --- DETEKSI DARK MODE ---
            const isDarkMode = document.documentElement.classList.contains('dark');

            const map = L.map('map', { preferCanvas: true, renderer: L.canvas() });
            map.setView([-2.548926, 118.0148634], 5);

            // ========== DEFINISI TILE LAYERS ==========
            const osmStandard = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap', maxZoom: 19 });
            const cartoLight = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap &copy; CARTO', maxZoom: 20 });
            const cartoDark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap &copy; CARTO', maxZoom: 20 });
            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri', maxZoom: 18 });
            const openTopo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { attribution: 'Map data: &copy; OpenStreetMap', maxZoom: 17 });

            // --- PILIH LAYER DEFAULT BERDASARKAN TEMA ---
            const defaultLayer = isDarkMode ? cartoDark : esriSatellite;
            defaultLayer.addTo(map);

            // ========== MARKERS DARI LARAVEL DATA ==========
            const lokasi = {!! json_encode($lokasiInstansi) !!};
            const markers = [];
            const markerLayer = L.layerGroup();
            
            if (lokasi && Array.isArray(lokasi)) {
                lokasi.forEach(function(l) {
                    if (l.latitude && l.longitude) {
                        const marker = L.marker([parseFloat(l.latitude), parseFloat(l.longitude)]);
                        // --- KONTEN POPUP DENGAN STYLE DARK MODE ---
                        const popupContent = `<div style="min-width: 200px;">
                                                <h4 style="margin: 0 0 8px 0; color: ${isDarkMode ? '#e5e7eb' : '#1f2937'}; font-weight: bold;">${l.nama_instansi || 'Instansi'}</h4>
                                                <p style="margin: 0 0 8px 0; font-size: 13px; color: ${isDarkMode ? '#9ca3af' : '#6b7280'};">Alamat: ${l.alamat}</p>
                                                <a href="{{ url('/instansi/detail') }}/${l.id}" style="display: inline-block; padding: 4px 12px; background-color: ${isDarkMode ? '#374151' : '#dbeafe'}; color: ${isDarkMode ? '#e5e7eb' : '#1e40af'}; font-size: 14px; border-radius: 6px; text-decoration: none;">Lihat Detail</a>
                                            </div>`;
                        marker.bindPopup(popupContent);
                        marker.addTo(markerLayer);
                        markers.push(marker);
                    }
                });
            }
            markerLayer.addTo(map);

            // ========== LAYER CONTROL ==========
            const baseLayers = {
                "🛰️ Satellite": esriSatellite,
                "🗺️ CartoDB Light": cartoLight,
                "🌃 CartoDB Dark": cartoDark,
                "🌍 OpenStreetMap": osmStandard,
                "⛰️ Topographic": openTopo,
            };
            const overlayLayers = { "📍 Lokasi Instansi": markerLayer };
            L.control.layers(baseLayers, overlayLayers, { position: 'topright', collapsed: false }).addTo(map);

            // ========== RENDERING FIX ==========
            function forceProperRendering() {
                setTimeout(() => map.invalidateSize(false), 100);
                setTimeout(() => {
                    map.invalidateSize(false);
                    if (markers.length > 0) {
                        const group = new L.featureGroup(markers);
                        if (group.getBounds().isValid()) {
                            map.fitBounds(group.getBounds(), { padding: [20, 20] });
                        }
                    }
                }, 300);
            }
            map.whenReady(forceProperRendering);
            window.addEventListener('resize', () => setTimeout(() => map.invalidateSize(false), 200));

            window.mapReady = true;
        } catch (error) {
            console.error('Critical error during map initialization:', error);
        }
    }
    </script>
</x-app-layout>
