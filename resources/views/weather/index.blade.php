@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <style>
        body {
            padding-top: 60px;
        }

        #map {
            width: 100%;
            height: calc(100vh - 60px);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .weather-icon {
            width: 40px;
            height: 40px;
        }

        .custom-popup .leaflet-popup-content-wrapper {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            font-size: 14px;
            line-height: 24px;
            border-radius: 10px;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.4);
        }

        .custom-popup .leaflet-popup-tip {
            background: rgba(255, 255, 255, 0.95);
        }

        .weather-control {
            position: absolute;
            bottom: 10px;
            left: 12px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            width: 250px;
        }

        .weather-control h6 {
            margin-bottom: 10px;
        }

        .legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            line-height: 18px;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="weather-control">
        <h6>Weather Settings</h6>
        <div class="mb-3">
            <label for="heatmapType" class="form-label">Heatmap Type</label>
            <select id="heatmapType" class="form-select">
                <option value="none">None</option> {{-- Added 'None' option --}}
                <option value="cloudiness">Cloudiness</option>
                <option value="temperature">Temperature</option>
                <option value="rainfall">Rainfall</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="heatmapRadius" class="form-label">Heatmap Radius: <span id="radiusValue">25</span></label>
            <input type="range" class="form-range" id="heatmapRadius" min="10" max="50" value="25">
        </div>
        <button id="refreshWeather" class="btn btn-primary btn-sm w-100">Refresh Data</button>
    </div>

    @foreach (['Point', 'Polyline', 'Polygon'] as $type)
        <div class="modal fade" id="Create{{ $type }}Modal" tabindex="-1"
            aria-labelledby="{{ $type }}ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="{{ $type }}ModalLabel">Create {{ $type }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route(strtolower($type) . 's.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="geom_{{ strtolower($type) }}" class="form-label">Geometry</label>
                                <textarea class="form-control" id="geom_{{ strtolower($type) }}" name="geom_{{ strtolower($type) }}" rows="3"
                                    readonly></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="weatherDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Weather Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.8.0/proj4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Initialize Map
        const map = L.map('map', {
            center: [-7.259264902509092, 112.75078785140347],
            zoom: 10
        });

        // Base Layer - Mapbox Streets
        const baseMap = L.tileLayer(
            'https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 18,
                accessToken: 'pk.eyJ1IjoiZmF1emlpbGFkaGltIiwiYSI6ImNtYzlveWg3YjIwZzQya3BuZ2R3MmJvcGsifQ.7lXfgMmh95mXAQSov_iSKg'
            }).addTo(map);

        // Layers
        const weatherLayer = L.layerGroup().addTo(map);
        const heatmapLayer = L.heatLayer([], {
            radius: 25,
            blur: 15,
            maxZoom: 17,
            gradient: {
                0.2: 'blue',
                0.5: 'yellow',
                0.8: 'red'
            }
        }).addTo(map);

        // New layer for direct GeoJSON rainfall data
        const curahHujanGeometryLayer = L.geoJSON(null, {
            style: function(feature) {
                // Parse curah_hujan and define style based on its value
                const curah_huja_str = feature.properties.curah_huja;
                const curah_hujan_value = parseFloat(curah_huja_str.replace(' mm/hari', '').replace(',', '.'));
                return {
                    fillColor: getRainfallColor(curah_hujan_value),
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.7
                };
            },
            onEachFeature: function(feature, layer) {
                const {
                    lokasi,
                    curah_huja,
                    tanggal,
                    kategori
                } = feature.properties;
                const popupContent = `
                    <div class="custom-popup">
                        <h6>${lokasi || 'N/A'}</h6>
                        <ul class="list-unstyled small mb-0">
                            <li><strong>🌧️ Curah Hujan:</strong> ${curah_huja || 'N/A'}</li>
                            <li><strong>📅 Tanggal:</strong> ${tanggal || 'N/A'}</li>
                            <li><strong>🏷️ Kategori:</strong> ${kategori || 'N/A'}</li>
                        </ul>
                    </div>
                `;
                layer.bindPopup(popupContent);
            }
        }).addTo(map); // Add this layer to the map initially

        const drawnItems = L.featureGroup().addTo(map);
        const pointLayer = L.geoJSON(null).addTo(map);
        const polylineLayer = L.geoJSON(null).addTo(map);
        const polygonLayer = L.geoJSON(null).addTo(map);
        const curahHujanClusterLayer = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false
        });
        const countriesLayer = L.geoJSON(null, {
            style: function(feature) {
                return {
                    color: '#333',
                    weight: 1.5,
                    opacity: 0.8,
                    fillColor: '#808080',
                    fillOpacity: 0.1
                };
            },
            onEachFeature: function(feature, layer) {
                if (feature.properties && feature.properties.name) {
                    layer.bindPopup('<strong>Negara:</strong> ' + feature.properties.name);
                }
            }
        });

        // Utility Functions
        const normalizeIntensity = (value, min, max) => Math.max(0, Math.min(1, (value - min) / (max - min)));

        const getFeatureCoordinates = (feature) => {
            const {
                type,
                coordinates
            } = feature.geometry;
            if (type === 'Point') return {
                lat: coordinates[1],
                lon: coordinates[0]
            };
            if (type === 'LineString') {
                const mid = Math.floor(coordinates.length / 2);
                return {
                    lat: coordinates[mid][1],
                    lon: coordinates[mid][0]
                };
            }
            if (type === 'Polygon' || type === 'MultiPolygon') { // Handle MultiPolygon for centroid calculation
                const centroid = turf.centroid(feature);
                return {
                    lat: centroid.geometry.coordinates[1],
                    lon: centroid.geometry.coordinates[0]
                };
            }
            return null;
        };

        // Rainfall Color Scale
        const getRainfallColor = (value) => {
            return value > 150 ? '#990000' :
                value > 100 ? '#ff3333' :
                value > 50 ? '#ff9966' :
                value > 20 ? '#33ccff' :
                '#0066cc';
        };

        // Coordinate Transformation (assuming UTM Zone 49S)
        proj4.defs('EPSG:32749', '+proj=utm +zone=49 +south +ellps=WGS84 +datum=WGS84 +units=m +no_defs');
        const utmToLatLng = (easting, northing) => {
            try {
                const [lon, lat] = proj4('EPSG:32749', 'EPSG:4326', [easting, northing]);
                return [lat, lon]; // Return [lat, lon] directly for Leaflet
            } catch (e) {
                console.error("Proj4JS conversion error:", e);
                return [NaN, NaN]; // Return NaN for invalid coordinates
            }
        };

        // API to GeoJSON and Heatmap Data Conversion
        const convertToHeatmapData = (data, lat, lon, type) => {
            let intensity;
            if (type === 'cloudiness') {
                intensity = normalizeIntensity(data.clouds.all, 0, 100);
            } else if (type === 'temperature') {
                intensity = normalizeIntensity(data.main.temp, 0, 40);
            } else if (type === 'rainfall' && data.rain) {
                intensity = normalizeIntensity(data.rain['1h'] || 0, 0, 100);
            } else {
                return null;
            }
            return intensity > 0.05 ? [lat, lon, intensity] : null;
        };

        // Rainfall Legend
        const legend = L.control({
            position: 'bottomright'
        });
        legend.onAdd = function(map) {
            const div = L.DomUtil.create('div', 'legend');
            const grades = [0, 20, 50, 100, 150];
            const labels = ['<strong>Curah Hujan (mm)</strong>'];

            for (let i = 0; i < grades.length; i++) {
                const from = grades[i];
                const to = grades[i + 1];
                labels.push(
                    `<i style="background:${getRainfallColor(from + 1)}"></i> ` +
                    `${from}${to ? `–${to}` : '+'}`
                );
            }

            div.innerHTML = labels.join('<br>');
            return div;
        };

        // Load Weather Data
        const loadWeatherData = async () => {
            try {
                $('#loadingOverlay').show();
                weatherLayer.clearLayers();
                // Clear heatmap if not rainfall type
                if ($('#heatmapType').val() !== 'rainfall') {
                    heatmapLayer.setLatLngs([]);
                }

                const areasResponse = await $.getJSON("{{ route('api.areas') }}");
                const weatherPromises = areasResponse.map(area =>
                    $.getJSON(`{{ url('api/weather') }}?lat=${area.lat}&lon=${area.lon}`)
                );
                const weatherResults = await Promise.allSettled(weatherPromises);
                const heatmapData = [];

                weatherResults.forEach((result, idx) => {
                    if (result.status === 'fulfilled') {
                        const weatherData = result.value;
                        const area = areasResponse[idx];
                        addWeatherMarker(area, weatherData);
                        const heatPoint = convertToHeatmapData(weatherData, area.lat, area.lon, $(
                            '#heatmapType').val());
                        if (heatPoint && $('#heatmapType').val() !== 'rainfall') {
                            heatmapData.push(heatPoint);
                        }
                    } else {
                        console.error(`Failed to fetch weather for area ${areasResponse[idx].name}:`, result
                            .reason);
                    }
                });

                if ($('#heatmapType').val() !== 'rainfall') {
                    heatmapLayer.setLatLngs(heatmapData);
                }
            } catch (error) {
                console.error('Error loading weather data:', error);
                alert('Failed to load weather data.');
            } finally {
                $('#loadingOverlay').hide();
            }
        };

        const addWeatherMarker = (area, weatherData) => {
            const heatmapType = $('#heatmapType').val();
            const weatherIcon = weatherData.weather[0].icon;
            const icon = L.divIcon({
                html: `<img src="https://openweathermap.org/img/wn/${weatherIcon}@2x.png" class="weather-icon">`,
                className: '',
                iconSize: [40, 40]
            });

            let popupContent = `<div class="custom-popup">
                                <h6>${area.name} <img src="https://openweathermap.org/img/wn/${weatherIcon}@2x.png" class="weather-icon" style="vertical-align: middle;"></h6>
                                <ul class="list-unstyled small mb-0">`;

            if (heatmapType === 'cloudiness') {
                popupContent += `
                    <li><strong>☁️ Cloudiness:</strong> ${weatherData.clouds.all}%</li>
                    <li><strong>👁️ Visibility:</strong> ${weatherData.visibility / 1000} km</li>
                    <li><strong>📝 Description:</strong> ${weatherData.weather[0].description}</li>
                `;
            } else if (heatmapType === 'temperature') {
                popupContent += `
                    <li><strong>🌡️ Temperature:</strong> ${weatherData.main.temp}°C</li>
                    <li><strong>🌡️ Feels Like:</strong> ${weatherData.main.feels_like}°C</li>
                    <li><strong>💧 Humidity:</strong> ${weatherData.main.humidity}%</li>
                `;
            } else if (heatmapType === 'rainfall') {
                const rainfall = weatherData.rain ? weatherData.rain['1h'] || 0 : 0;
                popupContent += `
                    <li><strong>🌧️ Rainfall (1h):</strong> ${rainfall} mm</li>
                    <li><strong>☁️ Cloudiness:</strong> ${weatherData.clouds.all}%</li>
                    <li><strong>📝 Description:</strong> ${weatherData.weather[0].description}</li>
                `;
            }

            popupContent += `
                    <li><strong>💨 Wind:</strong> ${weatherData.wind.speed} m/s</li>
                </ul>
            </div>`;

            L.marker([area.lat, area.lon], {
                    icon
                })
                .bindPopup(popupContent, {
                    className: 'custom-popup'
                })
                .addTo(weatherLayer);
        };

        const loadGeojsonData = async () => {
            try {
                $('#loadingOverlay').show();
                pointLayer.clearLayers();
                polylineLayer.clearLayers();
                polygonLayer.clearLayers();

                const [points, polylines, polygons] = await Promise.all([
                    $.getJSON("{{ route('api.points') }}"),
                    $.getJSON("{{ route('api.polylines') }}"),
                    $.getJSON("{{ route('api.polygons') }}")
                ]);

                pointLayer.addData(points);
                polylineLayer.addData(polylines);
                polygonLayer.addData(polygons);

                const geojsonLayers = [pointLayer, polylineLayer, polygonLayer];
                for (const layer of geojsonLayers) {
                    layer.eachLayer(async function(featureLayer) {
                        const feature = featureLayer.feature;
                        const coords = getFeatureCoordinates(feature);
                        if (coords) {
                            try {
                                const weatherData = await $.getJSON(
                                    `{{ url('api/weather') }}?lat=${coords.lat}&lon=${coords.lon}`);
                                const heatmapType = $('#heatmapType').val();
                                let popupHtml = `
                                <div class="custom-popup">
                                    <h6>${feature.properties.name || 'Lokasi GeoJSON'} <img src="https://openweathermap.org/img/wn/${weatherData.weather[0].icon}@2x.png" class="weather-icon" style="vertical-align: middle;"></h6>
                                    <ul class="list-unstyled small mb-0">`;

                                if (heatmapType === 'cloudiness') {
                                    popupHtml += `
                                        <li><strong>☁️ Cloudiness:</strong> ${weatherData.clouds.all}%</li>
                                        <li><strong>👁️ Visibility:</strong> ${weatherData.visibility / 1000} km</li>
                                        <li><strong>📝 Description:</strong> ${weatherData.weather[0].description}</li>
                                    `;
                                } else if (heatmapType === 'temperature') {
                                    popupHtml += `
                                        <li><strong>🌡️ Temp:</strong> ${weatherData.main.temp} °C</li>
                                        <li><strong>🌡️ Feels Like:</strong> ${weatherData.main.feels_like}°C</li>
                                        <li><strong>💧 Humidity:</strong> ${weatherData.main.humidity}%</li>
                                    `;
                                } else if (heatmapType === 'rainfall') {
                                    const rainfall = weatherData.rain ? weatherData.rain['1h'] || 0 : 0;
                                    popupHtml += `
                                        <li><strong>🌧️ Rainfall (1h):</strong> ${rainfall} mm</li>
                                        <li><strong>☁️ Cloudiness:</strong> ${weatherData.clouds.all}%</li>
                                        <li><strong>📝 Description:</strong> ${weatherData.weather[0].description}</li>
                                    `;
                                }
                                popupHtml += `
                                        <li><strong>💨 Wind:</strong> ${weatherData.wind.speed} m/s</li>
                                    </ul>
                                </div>`;
                                featureLayer.bindPopup(popupHtml, {
                                    className: 'custom-popup'
                                });
                            } catch (error) {
                                console.error(
                                    `Error fetching weather for GeoJSON feature ${feature.properties.name}:`,
                                    error);
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading GeoJSON data:', error);
                alert('Failed to load geospatial data.');
            } finally {
                $('#loadingOverlay').hide();
            }
        };

        const loadCountriesData = async () => {
            try {
                const countriesData = await $.getJSON("{{ asset('data/countries.geojson') }}");
                countriesLayer.addData(countriesData);
            } catch (error) {
                console.error("Error loading countries.geojson:", error);
            }
        };

        const loadCurahHujanData = async () => {
            try {
                $('#loadingOverlay').show();
                curahHujanGeometryLayer.clearLayers(); // Clear existing polygon layer
                curahHujanClusterLayer.clearLayers(); // Clear marker cluster layer (if used for points)
                heatmapLayer.setLatLngs([]); // Always clear heatmap before re-populating

                const data = await $.getJSON("{{ asset('data/curah_hujan.json') }}");

                const rainfallHeatmapPoints = []; // To store points for the rainfall heatmap

                data.features.forEach(feature => {
                    const {
                        geometry,
                        properties
                    } = feature;
                    const {
                        type: geometryType
                    } = geometry;
                    const {
                        lokasi,
                        curah_huja,
                        tanggal,
                        keterangan
                    } = properties; // Corrected 'keterangan' property

                    // Parse curah_hujan from string "16.68 mm/hari" to number
                    const curah_hujan_value = parseFloat(curah_huja.replace(' mm/hari', '').replace(',',
                        '.'));

                    if (isNaN(curah_hujan_value)) {
                        console.warn(
                            `Invalid curah_hujan value for '${lokasi || feature.id}': '${curah_huja}'. Skipping feature.`
                        );
                        return; // Skip this feature if rainfall value is invalid
                    }

                    // For direct geometry display (MultiPolygon in this case)
                    if (geometryType === 'MultiPolygon') {
                        let reprojectedFeature = feature;
                        // Check if the CRS is defined and is UTM
                        if (data.crs && data.crs.properties && data.crs.properties.name ===
                            'urn:ogc:def:crs:EPSG::32749') {
                            // Only reproject if the input CRS is UTM
                            try {
                                const transformedCoords = geometry.coordinates[0][0].map(coordPair => {
                                    // proj4.transform returns [lon, lat] in WGS84
                                    const [lon, lat] = proj4('EPSG:32749', 'EPSG:4326', coordPair);
                                    return [lon, lat]; // Keep as [lon, lat] for GeoJSON structure
                                });

                                reprojectedFeature = {
                                    ...feature,
                                    geometry: {
                                        type: 'MultiPolygon',
                                        coordinates: [
                                            [transformedCoords]
                                        ] // MultiPolygon expects nested arrays
                                    }
                                };
                            } catch (e) {
                                console.error(
                                    `Error reprojecting MultiPolygon feature '${lokasi || feature.id}':`,
                                    e);
                                return; // Skip if re-projection fails
                            }
                        }

                        curahHujanGeometryLayer.addData(reprojectedFeature);

                        // Also derive a centroid point for the heatmap
                        const centroid = turf.centroid(
                            reprojectedFeature); // Use the reprojected feature's centroid
                        const [lon, lat] = centroid.geometry.coordinates;

                        // Add to heatmap points if heatmap type is rainfall
                        if ($('#heatmapType').val() === 'rainfall') {
                            const intensity = normalizeIntensity(curah_hujan_value, 0, 200);
                            if (intensity > 0.05) {
                                rainfallHeatmapPoints.push([lat, lon, intensity]);
                            }
                        }

                    } else if (geometryType === 'Point' || geometryType === 'LineString' || geometryType ===
                        'MultiPoint') {


                        let lat, lon;
                        let tempLon, tempLat;

                        if (geometryType === 'Point') {
                            tempLon = geometry.coordinates[0];
                            tempLat = geometry.coordinates[1];
                        } else if (geometryType === 'LineString' || geometryType === 'MultiPoint') {
                            tempLon = geometry.coordinates[0][0];
                            tempLat = geometry.coordinates[0][1];
                        }

                        // Apply UTM conversion if needed
                        if (tempLon > 180 || tempLon < -180 || tempLat > 90 || tempLat < -90) {
                            [lat, lon] = utmToLatLng(tempLon, tempLat);
                        } else {
                            lat = tempLat;
                            lon = tempLon;
                        }

                        if (isNaN(lat) || isNaN(lon)) {
                            console.warn(
                                `Invalid computed LatLng for '${lokasi || feature.id}': (${lat}, ${lon}). Skipping.`
                            );
                            return;
                        }

                        // Add original point features (if any) to the cluster layer
                        const marker = L.circleMarker([lat, lon], {
                            radius: Math.min(8 + (curah_hujan_value / 50), 15),
                            fillColor: getRainfallColor(curah_hujan_value),
                            color: "#fff",
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8
                        });

                        const popupContent = `
                            <div class="custom-popup">
                                <h6>${lokasi || 'N/A'}</h6>
                                <ul class="list-unstyled small mb-0">
                                    <li><strong>🌧️ Curah Hujan:</strong> ${curah_huja || 'N/A'}</li>
                                    <li><strong>📅 Tanggal:</strong> ${tanggal || 'N/A'}</li>
                                    <li><strong>📝 Keterangan:</strong> ${keterangan || 'N/A'}</li>
                                </ul>
                            </div>
                        `;
                        marker.bindPopup(popupContent);
                        curahHujanClusterLayer.addLayer(marker);

                        // Also add to heatmap points if heatmap type is rainfall
                        if ($('#heatmapType').val() === 'rainfall') {
                            const intensity = normalizeIntensity(curah_hujan_value, 0, 200);
                            if (intensity > 0.05) {
                                rainfallHeatmapPoints.push([lat, lon, intensity]);
                            }
                        }

                    } else {
                        console.warn(
                            `Unsupported geometry type for feature '${lokasi || feature.id}': ${geometryType}. Skipping.`
                        );
                    }
                });

                if ($('#heatmapType').val() === 'rainfall') {
                    heatmapLayer.setLatLngs(rainfallHeatmapPoints);
                } else {
                    heatmapLayer.setLatLngs([]);
                }


            } catch (error) {
                console.error('Gagal memuat data curah hujan:', error);
                alert('Gagal memuat data curah hujan: ' + error.message);
            } finally {
                $('#loadingOverlay').hide();
            }
        };


        // Map Click Handler
        const weatherApiKey = "{{ config('services.openweather.key') }}";
        map.on('click', async (e) => {
            try {
                $('#loadingOverlay').show();
                const {
                    lat,
                    lng
                } = e.latlng;
                const url =
                    `https://api.openweathermap.org/data/2.5/weather?lat=${lat.toFixed(4)}&lon=${lng.toFixed(4)}&appid=${weatherApiKey}&units=metric`;
                const data = await $.getJSON(url);

                weatherLayer.clearLayers();
                const cityName = data.name || `Lat ${lat.toFixed(4)}, Lon ${lng.toFixed(4)}`;
                const iconUrl = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
                const iconMarker = L.icon({
                    iconUrl,
                    iconSize: [40, 40],
                    popupAnchor: [0, -20]
                });

                const heatmapType = $('#heatmapType').val();
                let popupHtml = `
                    <div class="card border-0">
                        <div class="card-body p-2">
                            <h6 class="card-title text-center mb-2">${cityName} <img src="${iconUrl}" class="weather-icon" style="vertical-align: middle;"></h6>
                            <ul class="list-unstyled small mb-0">
                `;

                if (heatmapType === 'cloudiness') {
                    popupHtml += `
                        <li><strong>☁️ Cloudiness:</strong> ${data.clouds.all}%</li>
                        <li><strong>👁️ Visibility:</strong> ${data.visibility / 1000} km</li>
                        <li><strong>📝 Description:</strong> ${data.weather[0].description}</li>
                    `;
                } else if (heatmapType === 'temperature') {
                    popupHtml += `
                        <li><strong>🌡️ Temp:</strong> ${data.main.temp} °C</li>
                        <li><strong>🌡️ Feels Like:</strong> ${data.main.feels_like}°C</li>
                        <li><strong>💧 Humidity:</strong> ${data.main.humidity}%</li>
                    `;
                } else if (heatmapType === 'rainfall') {
                    const rainfall = data.rain ? data.rain['1h'] || 0 : 0;
                    popupHtml += `
                        <li><strong>🌧️ Rainfall (1h):</strong> ${rainfall} mm</li>
                        <li><strong>☁️ Cloudiness:</strong> ${data.clouds.all}%</li>
                        <li><strong>📝 Description:</strong> ${data.weather[0].description}</li>
                    `;
                }

                popupHtml += `
                        <li><strong>💨 Wind:</strong> ${data.wind.speed} m/s</li>
                    </ul>
                </div>
            </div>`;

                L.marker([lat, lng], {
                        icon: iconMarker
                    })
                    .bindPopup(popupHtml, {
                        className: 'custom-popup'
                    })
                    .addTo(weatherLayer)
                    .openPopup();

                const type = $('#heatmapType').val();
                const heatPoint = convertToHeatmapData(data, lat, lng, type);
                if (heatPoint && type !==
                    'rainfall') { // Don't add to heatmap if rainfall is selected, as curah_hujan handles it
                    heatmapLayer.addLatLng(heatPoint);
                }
            } catch (error) {
                console.error('Error fetching weather data:', error);
                alert('Failed to fetch weather data.');
            } finally {
                $('#loadingOverlay').hide();
            }
        });

        // Layer Control
        const overlayMaps = {
            'Points': pointLayer,
            'Polylines': polylineLayer,
            'Polygons': polygonLayer,
            'Weather Icons': weatherLayer,
            'Heatmap': heatmapLayer,
            'Curah Hujan Geometri Magelang': curahHujanGeometryLayer,
            'Batas Negara': countriesLayer,
        };
        L.control.layers({
            'Mapbox Streets': baseMap
        }, overlayMaps, {
            collapsed: true
        }).addTo(map);

        // Event Handlers
        $('#heatmapType').on('change', () => {
            loadWeatherData();
            loadGeojsonData();
            loadCurahHujanData();
            loadCountriesData();

            // Manage legend visibility
            if ($('#heatmapType').val() === 'rainfall') {
                map.addControl(legend);
            } else {
                map.removeControl(legend);
            }

            // Manage heatmap layer visibility based on "none" option
            if ($('#heatmapType').val() === 'none') {
                if (map.hasLayer(heatmapLayer)) {
                    map.removeLayer(heatmapLayer);
                }
            } else {
                if (!map.hasLayer(heatmapLayer)) {
                    map.addLayer(heatmapLayer);
                }
            }
        });

        $('#heatmapRadius').on('input', function() {
            $('#radiusValue').text(this.value);
            heatmapLayer.setOptions({
                radius: parseInt(this.value)
            });
        });

        $('#refreshWeather').on('click', () => {
            loadWeatherData();
            loadGeojsonData();
            loadCurahHujanData();
            loadCountriesData();
        });

        // Initial Load
        $(document).ready(() => {
            loadWeatherData();
            loadGeojsonData();
            loadCurahHujanData();
            loadCountriesData();
            if ($('#heatmapType').val() === 'rainfall') {
                map.addControl(legend);
            } else {
                map.removeControl(legend);
            }
            if ($('#heatmapType').val() === 'none') {
                map.removeLayer(heatmapLayer);
            }
            setInterval(() => {
                loadWeatherData();
                loadGeojsonData();
                loadCurahHujanData();
            }, 600000); // Refresh every 10 minutes
        });
    </script>
@endsection
