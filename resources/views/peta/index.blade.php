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
    
    <!-- LEAFLET ROUTING MACHINE CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <style>
        /* Override dasar */
        #map { z-index: 1 !important; }
        .leaflet-container { font-family: inherit !important; }
        .leaflet-tile { max-width: none !important; }
        #map * { box-sizing: content-box !important; }
        #map img { max-width: none !important; height: auto !important; }

        /* --- DARK MODE STYLES UNTUK LEAFLET --- */

        /* Perbaikan untuk tile rendering - hapus filter yang menyebabkan masalah */
        .leaflet-tile-pane {
            filter: none !important;
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

        /* --- ROUTING MACHINE DARK MODE STYLES --- */
        .dark .leaflet-routing-container {
            background: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
            border: 1px solid rgb(55 65 81) !important;
        }
        
        .leaflet-routing-container {
            background: white !important;
            color: black !important;
        }
        
        .dark .leaflet-routing-container h2,
        .dark .leaflet-routing-container h3 {
            background: rgb(55 65 81) !important;
            color: rgb(229 231 235) !important;
        }
        
        .leaflet-routing-container h2,
        .leaflet-routing-container h3 {
            background: #f0f0f0 !important;
            color: black !important;
        }
        
        .dark .leaflet-routing-alt {
            background: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
            border-bottom: 1px solid rgb(55 65 81) !important;
        }
        
        .leaflet-routing-alt {
            background: white !important;
            color: black !important;
        }
        
        .dark .leaflet-routing-alt:hover {
            background: rgb(55 65 81) !important;
        }
        
        .leaflet-routing-alt:hover {
            background: #f0f0f0 !important;
        }
        
        .dark .leaflet-routing-alt-minimized {
            background: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
        }
        
        .leaflet-routing-alt-minimized {
            background: white !important;
            color: black !important;
        }
        
        .dark .leaflet-routing-geocoder input {
            background: rgb(55 65 81) !important;
            color: rgb(229 231 235) !important;
            border: 1px solid rgb(75 85 99) !important;
        }
        
        .leaflet-routing-geocoder input {
            background: white !important;
            color: black !important;
            border: 1px solid #ccc !important;
        }
        
        .dark .leaflet-routing-geocoder-result {
            background: rgb(31 41 55) !important;
            color: rgb(229 231 235) !important;
        }
        
        .leaflet-routing-geocoder-result {
            background: white !important;
            color: black !important;
        }
        
        .dark .leaflet-routing-geocoder-result:hover {
            background: rgb(55 65 81) !important;
        }
        
        .leaflet-routing-geocoder-result:hover {
            background: #f0f0f0 !important;
        }

        /* Routing Control Button Styling */
        .routing-toggle-btn {
            background: white;
            border: 2px solid rgba(0,0,0,0.2);
            border-radius: 4px;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .dark .routing-toggle-btn {
            background: rgb(31 41 55);
            color: rgb(229 231 235);
            border-color: rgb(55 65 81);
        }
        .routing-toggle-btn:hover {
            background: #f4f4f4;
        }
        .dark .routing-toggle-btn:hover {
            background: rgb(55 65 81);
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
            // Set view ke Banjarmasin (koordinat yang lebih tepat untuk Banjarmasin)
            map.setView([-3.3194374, 114.5925455], 12);

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
                        // --- KONTEN POPUP DENGAN STYLE DARK MODE DAN TOMBOL ROUTING ---
                        const popupContent = `<div style="min-width: 200px;">
                                                <h4 style="margin: 0 0 8px 0; color: ${isDarkMode ? '#e5e7eb' : '#1f2937'}; font-weight: bold;">${l.nama_instansi || 'Instansi'}</h4>
                                                <p style="margin: 0 0 8px 0; font-size: 13px; color: ${isDarkMode ? '#9ca3af' : '#6b7280'};">Alamat: ${l.alamat}</p>
                                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                                    <a href="{{ url('/instansi/detail') }}/${l.id}" style="display: inline-block; padding: 4px 12px; background-color: ${isDarkMode ? '#374151' : '#dbeafe'}; color: ${isDarkMode ? '#e5e7eb' : '#1e40af'}; font-size: 14px; border-radius: 6px; text-decoration: none;">Lihat Detail</a>
                                                    <button onclick="routeToLocation(${parseFloat(l.latitude)}, ${parseFloat(l.longitude)})" style="padding: 4px 12px; background-color: ${isDarkMode ? '#059669' : '#10b981'}; color: white; font-size: 14px; border: none; border-radius: 6px; cursor: pointer;">📍 Rute Kesini</button>
                                                </div>
                                            </div>`;
                        marker.bindPopup(popupContent);
                        marker.addTo(markerLayer);
                        markers.push(marker);
                    }
                });
            }
            markerLayer.addTo(map);

            // ========== ROUTING MACHINE SETUP ==========
            let routingControl = null;
            let routingEnabled = false;

            // Custom control untuk toggle routing
            const RoutingControl = L.Control.extend({
                onAdd: function(map) {
                    const container = L.DomUtil.create('div', 'leaflet-control-layers leaflet-control');
                    container.innerHTML = '<div class="routing-toggle-btn" title="Toggle Routing">🗺️</div>';
                    
                    L.DomEvent.on(container, 'click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        toggleRouting();
                    });
                    
                    return container;
                }
            });

            const routingControlButton = new RoutingControl({ position: 'topleft' });
            routingControlButton.addTo(map);

            // Function untuk toggle routing
            function toggleRouting() {
                if (routingEnabled) {
                    if (routingControl) {
                        map.removeControl(routingControl);
                        routingControl = null;
                    }
                    routingEnabled = false;
                } else {
                    // Buat routing control baru
                    routingControl = L.Routing.control({
                        waypoints: [],
                        routeWhileDragging: true,
                        addWaypoints: true,
                        draggableWaypoints: true,
                        fitSelectedRoutes: true,
                        showAlternatives: true,
                        altLineOptions: {
                            styles: [
                                {color: 'black', opacity: 0.15, weight: 9},
                                {color: 'white', opacity: 0.8, weight: 6},
                                {color: 'blue', opacity: 0.5, weight: 2}
                            ]
                        },
                        createMarker: function(i, waypoint, n) {
                            const marker = L.marker(waypoint.latLng, {
                                draggable: true,
                                bounceOnAdd: false,
                                bounceOnAddOptions: {
                                    duration: 1000,
                                    height: 800
                                },
                                icon: new L.Icon({
                                    iconUrl: i === 0 ? 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png' : 
                                            i === n-1 ? 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png' :
                                            'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                    iconSize: [25, 41],
                                    iconAnchor: [12, 41],
                                    popupAnchor: [1, -34],
                                    shadowSize: [41, 41]
                                })
                            });
                            
                            return marker;
                        },
                        lineOptions: {
                            styles: [
                                {color: 'red', opacity: 0.6, weight: 4}
                            ]
                        },
                        show: true,
                        collapsible: true,
                        collapsed: false
                    }).addTo(map);
                    
                    routingEnabled = true;

                    // Event listener untuk routing
                    routingControl.on('routesfound', function(e) {
                        const routes = e.routes;
                        const summary = routes[0].summary;
                        console.log('Route found:', summary);
                    });
                }
            }

            // Function untuk routing ke lokasi tertentu (dipanggil dari popup)
            window.routeToLocation = function(lat, lng) {
                if (!routingEnabled) {
                    toggleRouting();
                }
                
                if (routingControl) {
                    // Gunakan geolocation untuk mendapat posisi user sebagai starting point
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const userLat = position.coords.latitude;
                                const userLng = position.coords.longitude;
                                routingControl.setWaypoints([
                                    L.latLng(userLat, userLng),
                                    L.latLng(lat, lng)
                                ]);
                            },
                            function(error) {
                                // Jika geolocation gagal, gunakan center map sebagai starting point
                                const center = map.getCenter();
                                routingControl.setWaypoints([
                                    center,
                                    L.latLng(lat, lng)
                                ]);
                            }
                        );
                    } else {
                        // Fallback jika browser tidak support geolocation
                        const center = map.getCenter();
                        routingControl.setWaypoints([
                            center,
                            L.latLng(lat, lng)
                        ]);
                    }
                }
            };

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
                    // Tidak langsung fit bounds ke markers, biarkan fokus ke Banjarmasin
                }, 300);
            }
            map.whenReady(forceProperRendering);
            window.addEventListener('resize', () => setTimeout(() => map.invalidateSize(false), 200));

            window.mapReady = true;
            window.mapInstance = map; // Expose map instance globally
        } catch (error) {
            console.error('Critical error during map initialization:', error);
        }
    }
    </script>
</x-app-layout>