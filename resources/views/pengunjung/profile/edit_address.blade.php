@extends('layouts_pengunjung.main')
@section('title', 'Edit Alamat')

@section('content')
    <style>
        #map {
            height: 300px;
            width: 100%;
        }

        .search-results {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        .search-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .search-item:hover {
            background-color: #f8f9fa;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        .position-relative {
            position: relative;
        }
    </style>
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('profile.index') }}">Profil</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('profile.addresses') }}">Daftar Alamat</a>
                    <span class="breadcrumb-item active">Edit Alamat</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-dark mb-0">Edit Alamat</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.addresses.update', $address->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="keterangan">Label Alamat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan" name="keterangan" value="{{ old('keterangan', $address->keterangan) }}"
                                    placeholder="Contoh: Rumah, Kantor, dll.">
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Beri nama untuk alamat ini seperti "Rumah", "Kantor",
                                    dll.</small>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                    placeholder="Masukkan alamat lengkap">{{ old('alamat', $address->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group position-relative">
                                <label for="kode_pos_search">Cari berdasarkan Kode Pos <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_pos') is-invalid @enderror"
                                    id="kode_pos_search" value="{{ old('kode_pos', $address->kode_pos) }}"
                                    placeholder="Masukkan kode pos untuk mencari alamat">
                                <div id="searchResults" class="search-results" style="display: none;"></div>
                                @error('kode_pos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ketik kode pos untuk mencari dan mengisi otomatis data
                                    alamat</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="province_display">Provinsi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('provinsi') is-invalid @enderror"
                                            id="province_display" readonly placeholder="Provinsi akan terisi otomatis"
                                            value="{{ old('provinsi', $address->provinsi) }}">
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city_display">Kota/Kabupaten <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kota') is-invalid @enderror"
                                            id="city_display" readonly placeholder="Kota akan terisi otomatis"
                                            value="{{ old('kota', $address->kota) }}">
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kecamatan_display">Kecamatan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kecamatan') is-invalid @enderror"
                                            id="kecamatan_display" readonly placeholder="Kecamatan akan terisi otomatis"
                                            value="{{ old('kecamatan', $address->kecamatan) }}">
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_pos_display">Kode Pos</label>
                                        <input type="text" class="form-control" id="kode_pos_display" readonly
                                            placeholder="Kode pos akan terisi otomatis"
                                            value="{{ old('kode_pos', $address->kode_pos) }}">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="provinsi" id="provinsi"
                                value="{{ old('provinsi', $address->provinsi) }}">
                            <input type="hidden" name="kota" id="kota" value="{{ old('kota', $address->kota) }}">
                            <input type="hidden" name="kecamatan" id="kecamatan"
                                value="{{ old('kecamatan', $address->kecamatan) }}">
                            <input type="hidden" name="kode_pos" id="kode_pos"
                                value="{{ old('kode_pos', $address->kode_pos) }}">
                            <input type="hidden" name="province_id" id="province_id"
                                value="{{ old('province_id', $address->province_id) }}">
                            <input type="hidden" name="city_id" id="city_id"
                                value="{{ old('city_id', $address->city_id) }}">
                            <input type="hidden" name="district_id" id="district_id"
                                value="{{ old('district_id', $address->district_id) }}">

                            <div class="card mb-3 mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Lokasi di Peta</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Anda dapat mengubah lokasi dengan mengklik pada
                                        peta atau menggunakan lokasi saat ini.
                                    </div>

                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-info" id="getCurrentLocation">
                                            <i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini
                                        </button>
                                    </div>

                                    <div id="map" class="border rounded mb-3"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="latitude">Latitude</label>
                                                <input type="text" class="form-control" id="latitude"
                                                    name="latitude" value="{{ old('latitude', $address->latitude) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="longitude">Longitude</label>
                                                <input type="text" class="form-control" id="longitude"
                                                    name="longitude" value="{{ old('longitude', $address->longitude) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.addresses') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;
        let searchTimeout;

        document.addEventListener('DOMContentLoaded', function() {
            const savedLat = document.getElementById('latitude').value;
            const savedLng = document.getElementById('longitude').value;
            const initialLat = savedLat || -0.789275;
            const initialLng = savedLng || 113.921327;
            const initialZoom = savedLat && savedLng ? 15 : 5;

            map = L.map('map').setView([initialLat, initialLng], initialZoom);

            const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google Maps'
            }).addTo(map);

            if (savedLat && savedLng) {
                marker = L.marker([savedLat, savedLng], {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', updateCoordinates);
            }

            map.on('click', function(e) {
                placeMarker(e.latlng);
            });

            document.getElementById('getCurrentLocation').addEventListener('click', function() {
                getUserLocation();
            });

            const zipCodeInput = document.getElementById('kode_pos_search');
            const searchResults = document.getElementById('searchResults');

            zipCodeInput.addEventListener('input', function() {
                const zipCode = this.value.trim();

                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                if (zipCode.length >= 3) {
                    searchTimeout = setTimeout(() => {
                        searchByZipCode(zipCode);
                    }, 500);
                } else {
                    searchResults.style.display = 'none';
                    searchResults.innerHTML = '';
                }
            });

            document.addEventListener('click', function(e) {
                if (!zipCodeInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });
        });

        function searchByZipCode(zipCode) {
            const searchResults = document.getElementById('searchResults');

            searchResults.innerHTML = '<div class="search-item">Mencari...</div>';
            searchResults.style.display = 'block';

            fetch(`{{ route('rajaongkir.search-zip') }}?zip_code=${zipCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        displaySearchResults(data.data);
                    } else {
                        searchResults.innerHTML = '<div class="search-item">Tidak ada hasil ditemukan</div>';
                    }
                })
                .catch(error => {
                    console.error('Error searching zip code:', error);
                    searchResults.innerHTML = '<div class="search-item">Terjadi kesalahan saat mencari</div>';
                });
        }

        function displaySearchResults(results) {
            const searchResults = document.getElementById('searchResults');

            searchResults.innerHTML = '';

            results.forEach(item => {
                const div = document.createElement('div');
                div.className = 'search-item';
                div.innerHTML = `
                    <strong>${item.label}</strong><br>
                    <small>Kecamatan: ${item.district_name}, Kelurahan: ${item.subdistrict_name}</small>
                `;

                div.addEventListener('click', function() {
                    selectAddress(item);
                });

                searchResults.appendChild(div);
            });

            searchResults.style.display = 'block';
        }

        function selectAddress(item) {
            document.getElementById('province_display').value = item.province_name;
            document.getElementById('city_display').value = item.city_name;
            document.getElementById('kecamatan_display').value = item.district_name;
            document.getElementById('kode_pos_display').value = item.zip_code;
            document.getElementById('kode_pos_search').value = item.zip_code;

            document.getElementById('provinsi').value = item.province_name;
            document.getElementById('kota').value = item.city_name;
            document.getElementById('kecamatan').value = item.district_name;
            document.getElementById('kode_pos').value = item.zip_code;

            document.getElementById('district_id').value = item.id;
            document.getElementById('province_id').value = '';
            document.getElementById('city_id').value = '';

            document.getElementById('searchResults').style.display = 'none';
        }

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const latlng = L.latLng(lat, lng);

                        map.setView(latlng, 15);
                        placeMarker(latlng);
                    },
                    function(error) {
                        console.error('Error getting location:', error.message);
                        alert('Tidak dapat mendapatkan lokasi Anda. Silahkan klik pada peta untuk menandai lokasi.');
                    }
                );
            } else {
                alert('Geolocation tidak didukung di browser Anda. Silahkan klik pada peta untuk menandai lokasi.');
            }
        }

        function placeMarker(latlng) {
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker(latlng, {
                draggable: true
            }).addTo(map);

            document.getElementById('latitude').value = latlng.lat.toFixed(6);
            document.getElementById('longitude').value = latlng.lng.toFixed(6);

            marker.on('dragend', updateCoordinates);
        }

        function updateCoordinates() {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(6);
            document.getElementById('longitude').value = position.lng.toFixed(6);
        }
    </script>
@endsection
