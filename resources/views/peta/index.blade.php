<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peta Lokasi Instansi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="map" style="height: 70vh; width: 100%; min-height: 500px; position: relative; border: 1px solid #ddd; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- LEAFLET CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        /* Override semua CSS yang mungkin interfere */
        #map {
            height: 70vh !important;
            width: 100% !important;
            min-height: 500px !important;
            position: relative !important;
            z-index: 1 !important;
        }
        
        .leaflet-container {
            height: 100% !important;
            width: 100% !important;
            font-family: inherit !important;
        }
        
        .leaflet-control-container {
            position: relative !important;
            z-index: 1000 !important;
        }
        
        .leaflet-tile {
            max-width: none !important;
            image-rendering: auto !important;
        }
        
        /* Override Tailwind/Bootstrap yang mungkin interfere */
        #map * {
            box-sizing: content-box !important;
        }
        
        #map img {
            max-width: none !important;
            height: auto !important;
        }

        /* Custom styling untuk layer control */
        .leaflet-control-layers {
            border-radius: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }

        .leaflet-control-layers-expanded {
            min-width: 180px;
        }

        .leaflet-control-layers label {
            font-size: 14px;
            padding: 4px 0;
        }
    </style>

    <script type="text/javascript">
    // Tunggu sampai SEMUA assets Laravel loaded
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            initMap();
        }, 500);
    });

    // Backup initialization
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (!window.mapReady) {
                initMap();
            }
        }, 1000);
    });

    function initMap() {
        try {
            // Prevent multiple initialization
            if (window.mapReady) {
                console.log('Map already initialized');
                return;
            }

            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                return;
            }

            // Force clear any existing Leaflet instance
            if (mapContainer._leaflet_id) {
                delete mapContainer._leaflet_id;
            }
            mapContainer.innerHTML = '';
            
            console.log('Initializing Leaflet map with multiple layers...');

            // Create map
            const map = L.map('map', {
                preferCanvas: true,
                renderer: L.canvas(),
                zoomControl: true,
                attributionControl: true
            });

            // Set initial view
            map.setView([-2.548926, 118.0148634], 5);

            // ========== DEFINISI MULTIPLE TILE LAYERS ==========
            
            // 1. OpenStreetMap Standard
            const osmStandard = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
                name: 'OpenStreetMap'
            });

            // 2. CartoDB Light (Clean & minimalist)
            const cartoLight = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20,
                name: 'CartoDB Light'
            });

            // 3. CartoDB Dark
            const cartoDark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20,
                name: 'CartoDB Dark'
            });

            // 4. CartoDB Voyager (Colorful)
            const cartoVoyager = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20,
                name: 'CartoDB Voyager'
            });

            // 5. Esri World Imagery (Satellite)
            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                maxZoom: 18,
                name: 'Satellite (Esri)'
            });

            // 6. Esri World Street Map
            const esriStreet = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom',
                maxZoom: 18,
                name: 'Esri Street'
            });

            // 7. OpenTopoMap (Topographic)
            const openTopo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
                maxZoom: 17,
                name: 'Topographic'
            });

            // Set default layer (CartoDB Light - paling clean)
            esriSatellite.addTo(map);

            // ========== MARKERS DARI LARAVEL DATA ==========
            const lokasi = {!! json_encode($lokasiInstansi) !!};
            const markers = [];
            const markerLayer = L.layerGroup(); // Group untuk markers
            
            if (lokasi && Array.isArray(lokasi)) {
                lokasi.forEach(function(l) {
                    if (l.latitude && l.longitude) {
                        try {
                            const marker = L.marker([parseFloat(l.latitude), parseFloat(l.longitude)]);
                            const popupContent = '<div style="min-width: 200px;">' +
                                                '<h4 style="margin: 0 0 8px 0; color: #1f2937; font-weight: bold;">' + (l.nama_instansi || 'Instansi') + '</h4>' +
                                                '<p style="margin: 0 0 8px 0; font-size: 13px; color: #6b7280;">Koordinat: ' + l.latitude + ', ' + l.longitude + '</p>' +
                                                '<a href="{{ url('/instansi/detail') }}/' + l.id + '" class="inline-block px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors">Lihat Detail</a>' +
                                                '</div>';
                            marker.bindPopup(popupContent);
                            marker.addTo(markerLayer);
                            markers.push(marker);
                        } catch (markerError) {
                            console.warn('Error adding marker:', markerError, l);
                        }
                    }
                });
            }

            markerLayer.addTo(map);
            console.log('Added ' + markers.length + ' markers');

            // ========== LAYER CONTROL ==========
            const baseLayers = {
                "🛰️ Satellite": esriSatellite,
                "🌍 OpenStreetMap": osmStandard,
                "🗺️ CartoDB Light": cartoLight,
                "🗾 CartoDB Voyager": cartoVoyager,
                "🌃 CartoDB Dark": cartoDark,
                "🏘️ Esri Street": esriStreet,
                "⛰️ Topographic": openTopo,
            };

            const overlayLayers = {
                "📍 Lokasi Instansi": markerLayer
            };

            // Tambahkan layer control
            const layerControl = L.control.layers(baseLayers, overlayLayers, {
                position: 'topright',
                collapsed: false // Biarkan terbuka agar mudah digunakan
            }).addTo(map);

            // ========== ERROR HANDLING UNTUK TILES ==========
            Object.values(baseLayers).forEach(layer => {
                layer.on('tileerror', function(e) {
                    console.warn('Tile loading error for layer:', layer.options.name || 'Unknown', e);
                });
                
                layer.on('tileload', function() {
                    // console.log('Tiles loaded for:', layer.options.name || 'Unknown layer');
                });
            });

            // ========== RENDERING FIX ==========
            function forceProperRendering() {
                console.log('Forcing map rendering...');
                
                setTimeout(() => {
                    map.invalidateSize({ animate: false, pan: false });
                }, 100);
                
                setTimeout(() => {
                    map.invalidateSize({ animate: false, pan: false });
                    
                    // Fit bounds jika ada markers
                    if (markers.length > 0) {
                        try {
                            const group = new L.featureGroup(markers);
                            const bounds = group.getBounds();
                            if (bounds.isValid()) {
                                map.fitBounds(bounds, { padding: [20, 20] });
                            }
                        } catch (boundsError) {
                            console.warn('Error fitting bounds:', boundsError);
                        }
                    }
                }, 300);
                
                setTimeout(() => {
                    map.invalidateSize({ animate: false, pan: false });
                }, 600);
                
                setTimeout(() => {
                    map.invalidateSize({ animate: false, pan: false });
                    map.panBy([0, 0]); // Force repaint
                }, 1000);
            }

            // Execute rendering fix when map is ready
            map.whenReady(function() {
                console.log('Map is ready');
                forceProperRendering();
            });

            // Backup rendering fix
            setTimeout(forceProperRendering, 1500);

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    map.invalidateSize({ animate: false, pan: false });
                }, 200);
            });

            // ========== EVENT HANDLERS ==========
            
            // Handle layer change
            map.on('baselayerchange', function(e) {
                console.log('Base layer changed to:', e.name);
                // Force refresh setelah ganti layer
                setTimeout(() => {
                    map.invalidateSize({ animate: false, pan: false });
                }, 100);
            });

            // Handle overlay toggle
            map.on('overlayadd', function(e) {
                console.log('Overlay added:', e.name);
            });

            map.on('overlayremove', function(e) {
                console.log('Overlay removed:', e.name);
            });

            // Mark as ready
            window.mapReady = true;
            window.leafletMap = map;
            window.mapLayers = baseLayers; // Store untuk debugging
            
            console.log('Map initialization completed successfully with', Object.keys(baseLayers).length, 'base layers');

        } catch (error) {
            console.error('Critical error during map initialization:', error);
            
            // Fallback - show error message
            const mapContainer = document.getElementById('map');
            if (mapContainer) {
                mapContainer.innerHTML = 
                    '<div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f9fafb; border: 2px dashed #d1d5db; color: #6b7280; text-align: center; padding: 20px; border-radius: 8px;">' +
                    '<div>' +
                    '<h3 style="margin: 0 0 10px 0; color: #374151;">Peta tidak dapat dimuat</h3>' +
                    '<p style="margin: 0; font-size: 14px;">Error: ' + error.message + '</p>' +
                    '<button onclick="location.reload()" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">Refresh Halaman</button>' +
                    '</div>' +
                    '</div>';
            }
        }
    }
    </script>
</x-app-layout>
