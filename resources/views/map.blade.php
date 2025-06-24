@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        /* Kontainer peta mengisi area di bawah navbar */
        #map {
            position: absolute;
            top: 56px; /* sesuaikan dengan tinggi navbar */
            bottom: 0;
            left: 0;
            right: 0;
        }
        /* Styling modal, tombol, dan gambar */
        .modal-content {
            border-radius: 10px;
            background-color: #f8f9fa;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .modal-body label {
            font-weight: 500;
            color: #333;
        }
        .img-fluid {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        @media (max-width: 768px) {
            #map {
                top: 56px;
            }
            .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Point -->
    <div class="modal fade" id="CreatePointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="example point" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <img src="" alt="" id="preview-image-point" class="img-thumbnail" width="400">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Polyline -->
    <div class="modal fade" id="CreatePolylineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polyline</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polylines.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="example polylines" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geom_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polyline" name="geom_polyline" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polyline" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <img src="" alt="" id="preview-image-polyline" class="img-thumbnail" width="400">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Polygon -->
    <div class="modal fade" id="CreatePolygonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polygon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polygons.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="example polygon" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geom_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polygon" name="geom_polygon" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polygon" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <img src="" alt="" id="preview-image-polygon" class="img-thumbnail" width="400">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-7.259264902509092, 112.75078785140347], 15);

        // Base Map Layer with Mapbox
        var baseMap = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 18,
            accessToken: 'pk.eyJ1IjoiZmF1emlpbGFkaGltIiwiYSI6ImNtYzlveWg3YjIwZzQya3BuZ2R3MmJvcGsifQ.7lXfgMmh95mXAQSov_iSKg' // Replace with your Mapbox access token
        }).addTo(map);

        // Simpan kunci API dari konfigurasi Laravel dengan aman
        var weatherApiKey = "{{ config('services.openweather.key') }}";

        // =================================================================================================
        // ANALISIS CUACA GLOBAL DARI OPENWEATHERMAP API
        // =================================================================================================
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lon = e.latlng.lng;
            var weatherApiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${weatherApiKey}&units=metric`;

            $.getJSON(weatherApiUrl, function(data) {
                if (data && data.weather && data.main) {
                    const temp = data.main.temp;
                    const weatherDesc = data.weather[0].description;
                    const weatherIcon = data.weather[0].icon;
                    const cityName = data.name || "Lokasi";
                    const windSpeed = data.wind.speed;
                    const humidity = data.main.humidity;

                    const iconUrl = `https://openweathermap.org/img/wn/${weatherIcon}@2x.png`;

                    var weatherIconMarker = L.icon({
                        iconUrl: iconUrl,
                        iconSize: [50, 50],
                        popupAnchor: [0, -20]
                    });

                    var popupContent = `
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">${cityName}</h5>
                                <hr>
                                <p><strong>Cuaca:</strong> ${weatherDesc}</p>
                                <p><strong>Temperatur:</strong> ${temp} °C</p>
                                <p><strong>Kelembapan:</strong> ${humidity}%</p>
                                <p><strong>Kecepatan Angin:</strong> ${windSpeed} m/s</p>
                            </div>
                        </div>
                    `;

                    var marker = L.marker([lat, lon], { icon: weatherIconMarker }).bindPopup(popupContent).addTo(weatherLayer);
                    marker.openPopup();
                } else {
                    alert("Gagal mengambil data cuaca untuk lokasi ini.");
                }
            }).fail(function() {
                alert("Terjadi kesalahan saat menghubungi API cuaca.");
            });
        });

        // GeoJSON Point
        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                layer.on({
                    click: function(e) {
                        var lat = feature.geometry.coordinates[1];
                        var lon = feature.geometry.coordinates[0];
                        var weatherApiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${weatherApiKey}&units=metric`;

                        $.getJSON(weatherApiUrl, function(data) {
                            var weatherInfo = '';
                            if (data && data.weather && data.main) {
                                const temp = data.main.temp;
                                const weatherDesc = data.weather[0].description;
                                const humidity = data.main.humidity;
                                const windSpeed = data.wind.speed;

                                weatherInfo = `
                                    <hr>
                                    <p><strong>Cuaca Saat Ini:</strong> ${weatherDesc}</p>
                                    <p><strong>Temperatur:</strong> ${temp} °C</p>
                                    <p><strong>Kelembapan:</strong> ${humidity}%</p>
                                    <p><strong>Kecepatan Angin:</strong> ${windSpeed} m/s</p>
                                `;
                            } else {
                                weatherInfo = '<p>Gagal mengambil data cuaca.</p>';
                            }

                            var routedelete = "{{ route('points.destroy', ':id') }}".replace(':id', feature.properties.id);
                            var routeedit = "{{ route('points.edit', ':id') }}".replace(':id', feature.properties.id);

                            var popupContent = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${feature.properties.name}</h5>
                                        <p class="card-text">${feature.properties.description}</p>
                                        <p class="card-text"><small class="text-muted">Dibuat: ${feature.properties.created_at}</small></p>
                                        <img src="{{ asset('storage/images') }}/${feature.properties.image}" class="img-fluid" alt="Foto">
                                        ${weatherInfo}
                                        <div class="mt-3">
                                            <a href="${routeedit}" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                            <form method="POST" action="${routedelete}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this Point?')"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                            </form>
                                        </div>
                                        <p class="mt-2"><small>Dibuat Oleh: ${feature.properties.user_created}</small></p>
                                    </div>
                                </div>
                            `;

                            layer.bindPopup(popupContent).openPopup();
                        }).fail(function() {
                            var routedelete = "{{ route('points.destroy', ':id') }}".replace(':id', feature.properties.id);
                            var routeedit = "{{ route('points.edit', ':id') }}".replace(':id', feature.properties.id);

                            var popupContent = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${feature.properties.name}</h5>
                                        <p class="card-text">${feature.properties.description}</p>
                                        <p class="card-text"><small class="text-muted">Dibuat: ${feature.properties.created_at}</small></p>
                                        <img src="{{ asset('storage/images') }}/${feature.properties.image}" class="img-fluid" alt="Foto">
                                        <p>Gagal mengambil data cuaca.</p>
                                        <div class="mt-3">
                                            <a href="${routeedit}" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                            <form method="POST" action="${routedelete}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this Point?')"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                            </form>
                                        </div>
                                        <p class="mt-2"><small>Dibuat Oleh: ${feature.properties.user_created}</small></p>
                                    </div>
                                </div>
                            `;

                            layer.bindPopup(popupContent).openPopup();
                        });
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    }
                });
            }
        });
        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        // GeoJSON Polyline
        var polyline = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                layer.on({
                    click: function(e) {
                        var coordinates = feature.geometry.coordinates;
                        var start = coordinates[0];
                        var end = coordinates[coordinates.length - 1];
                        var latStart = start[1];
                        var lonStart = start[0];
                        var latEnd = end[1];
                        var lonEnd = end[0];

                        var weatherApiUrlStart = `https://api.openweathermap.org/data/2.5/weather?lat=${latStart}&lon=${lonStart}&appid=${weatherApiKey}&units=metric`;
                        var weatherApiUrlEnd = `https://api.openweathermap.org/data/2.5/weather?lat=${latEnd}&lon=${lonEnd}&appid=${weatherApiKey}&units=metric`;

                        $.when(
                            $.getJSON(weatherApiUrlStart),
                            $.getJSON(weatherApiUrlEnd)
                        ).done(function(dataStart, dataEnd) {
                            var weatherInfo = '';
                            if (dataStart[0] && dataStart[0].weather && dataStart[0].main && dataEnd[0] && dataEnd[0].weather && dataEnd[0].main) {
                                const tempStart = dataStart[0].main.temp;
                                const weatherDescStart = dataStart[0].weather[0].description;
                                const humidityStart = dataStart[0].main.humidity;
                                const windSpeedStart = dataStart[0].wind.speed;

                                const tempEnd = dataEnd[0].main.temp;
                                const weatherDescEnd = dataEnd[0].weather[0].description;
                                const humidityEnd = dataEnd[0].main.humidity;
                                const windSpeedEnd = dataEnd[0].wind.speed;

                                weatherInfo = `
                                    <hr>
                                    <h6>Titik Awal</h6>
                                    <p><strong>Cuaca:</strong> ${weatherDescStart}</p>
                                    <p><strong>Temperatur:</strong> ${tempStart} °C</p>
                                    <p><strong>Kelembapan:</strong> ${humidityStart}%</p>
                                    <p><strong>Kecepatan Angin:</strong> ${windSpeedStart} m/s</p>
                                    <h6>Titik Akhir</h6>
                                    <p><strong>Cuaca:</strong> ${weatherDescEnd}</p>
                                    <p><strong>Temperatur:</strong> ${tempEnd} °C</p>
                                    <p><strong>Kelembapan:</strong> ${humidityEnd}%</p>
                                    <p><strong>Kecepatan Angin:</strong> ${windSpeedEnd} m/s</p>
                                `;
                            } else {
                                weatherInfo = '<p>Gagal mengambil data cuaca untuk polyline ini.</p>';
                            }

                            var routedelete = "{{ route('polylines.destroy', ':id') }}".replace(':id', feature.properties.id);
                            var routeedit = "{{ route('polylines.edit', ':id') }}".replace(':id', feature.properties.id);

                            var popupContent = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${feature.properties.name}</h5>
                                        <p class="card-text">${feature.properties.description}</p>
                                        <p class="card-text"><small class="text-muted">Dibuat: ${feature.properties.created_at}</small></p>
                                        <p><strong>Panjang:</strong> ${feature.properties.length_km.toFixed(2)} km</p>
                                        <img src="{{ asset('storage/images') }}/${feature.properties.image}" class="img-fluid" alt="Foto">
                                        ${weatherInfo}
                                        <div class="mt-3">
                                            <a href="${routeedit}" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                            <form method="POST" action="${routedelete}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this Polyline?')"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                            </form>
                                        </div>
                                        <p class="mt-2"><small>Dibuat Oleh: ${feature.properties.user_created}</small></p>
                                    </div>
                                </div>
                            `;

                            layer.bindPopup(popupContent).openPopup();
                        }).fail(function() {
                            var routedelete = "{{ route('polylines.destroy', ':id') }}".replace(':id', feature.properties.id);
                            var routeedit = "{{ route('polylines.edit', ':id') }}".replace(':id', feature.properties.id);

                            var popupContent = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${feature.properties.name}</h5>
                                        <p class="card-text">${feature.properties.description}</p>
                                        <p class="card-text"><small class="text-muted">Dibuat: ${feature.properties.created_at}</small></p>
                                        <p><strong>Panjang:</strong> ${feature.properties.length_km.toFixed(2)} km</p>
                                        <img src="{{ asset('storage/images') }}/${feature.properties.image}" class="img-fluid" alt="Foto">
                                        <p>Gagal mengambil data cuaca.</p>
                                        <div class="mt-3">
                                            <a href="${routeedit}" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                            <form method="POST" action="${routedelete}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this Polyline?')"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                            </form>
                                        </div>
                                        <p class="mt-2"><small>Dibuat Oleh: ${feature.properties.user_created}</small></p>
                                    </div>
                                </div>
                            `;

                            layer.bindPopup(popupContent).openPopup();
                        });
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    }
                });
            }
        });
        $.getJSON("{{ route('api.polylines') }}", function(data) {
            polyline.addData(data);
            map.addLayer(polyline);
        });

        // GeoJSON Polygon
        var polygon = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                layer.on({
                    click: function(e) {
                        var centroid = turf.centroid(feature).geometry.coordinates;
                        var lat = centroid[1];
                        var lon = centroid[0];
                        var weatherApiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${weatherApiKey}&units=metric`;

                        $.getJSON(weatherApiUrl, function(data) {
                            var weatherInfo = '';
                            if (data && data.weather && data.main) {
                                const temp = data.main.temp;
                                const weatherDesc = data.weather[0].description;
                                const humidity = data.main.humidity;
                                const windSpeed = data.wind.speed;

                                weatherInfo = `
                                    <hr>
                                    <p><strong>Cuaca di Centroid:</strong> ${weatherDesc}</p>
                                    <p><strong>Temperatur:</strong> ${temp} °C</p>
                                    <p><strong>Kelembapan:</strong> ${humidity}%</p>
                                    <p><strong>Kecepatan Angin:</strong> ${windSpeed} m/s</p>
                                `;
                            } else {
                                weatherInfo = '<p>Gagal mengambil data cuaca.</p>';
                            }

                            var routedelete = "{{ route('polygons.destroy', ':id') }}".replace(':id', feature.properties.id);
                            var routeedit = "{{ route('polygons.edit', ':id') }}".replace(':id', feature.properties.id);

                            var popupContent = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${feature.properties.name}</h5>
                                        <p class="card-text">${feature.properties.description}</p>
                                        <p class="card-text"><small class="text-muted">Dibuat: ${feature.properties.created_at}</small></p>
                                        <p><strong>Luas:</strong> ${Number(feature.properties.luas_km2).toFixed(2)} km²</p>
                                        <img src="{{ asset('storage/images') }}/${feature.properties.image}" class="img-fluid" alt="Foto">
                                        ${weatherInfo}
                                        <div class="mt-3">
                                            <a href="${routeedit}" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                            <form method="POST" action="${routedelete}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this Polygon?')"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                            </form>
                                        </div>
                                        <p class="mt-2"><small>Dibuat Oleh: ${feature.properties.user_created}</small></p>
                                    </div>
                                </div>
                            `;

                            layer.bindPopup(popupContent).openPopup();
                        }).fail(function() {
                            var routedelete = "{{ route('polygons.destroy', ':id') }}".replace(':id', feature.properties.id);
                            var routeedit = "{{ route('polygons.edit', ':id') }}".replace(':id', feature.properties.id);

                            var popupContent = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${feature.properties.name}</h5>
                                        <p class="card-text">${feature.properties.description}</p>
                                        <p class="card-text"><small class="text-muted">Dibuat: ${feature.properties.created_at}</small></p>
                                        <p><strong>Luas:</strong> ${Number(feature.properties.luas_km2).toFixed(2)} km²</p>
                                        <img src="{{ asset('storage/images') }}/${feature.properties.image}" class="img-fluid" alt="Foto">
                                        <p>Gagal mengambil data cuaca.</p>
                                        <div class="mt-3">
                                            <a href="${routeedit}" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                            <form method="POST" action="${routedelete}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this Polygon?')"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                            </form>
                                        </div>
                                        <p class="mt-2"><small>Dibuat Oleh: ${feature.properties.user_created}</small></p>
                                    </div>
                                </div>
                            `;

                            layer.bindPopup(popupContent).openPopup();
                        });
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    }
                });
            }
        });
        $.getJSON("{{ route('api.polygons') }}", function(data) {
            polygon.addData(data);
            map.addLayer(polygon);
        });

        // Kontrol Layer
        var baseMaps = {
            "Mapbox Streets": baseMap
        };

        var overlayMaps = {
            "Points": point,
            "Polylines": polyline,
            "Polygons": polygon
        };

        L.control.layers(baseMaps, overlayMaps, {
            position: 'topright'
        }).addTo(map);

        // Digitize Function
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            console.log(objectGeometry);

            if (type === 'polyline') {
                console.log("Create " + type);
                $('#geom_polyline').val(objectGeometry);
                $('#CreatePolylineModal').modal('show');
            } else if (type === 'polygon' || type === 'rectangle') {
                console.log("Create " + type);
                $('#geom_polygon').val(objectGeometry);
                $('#CreatePolygonModal').modal('show');
            } else if (type === 'marker') {
                console.log("Create " + type);
                $('#geom_point').val(objectGeometry);
                $('#CreatePointModal').modal('show');
            } else {
                console.log('__undefined__');
            }

            drawnItems.addLayer(layer);
        });
    </script>
@endsection
