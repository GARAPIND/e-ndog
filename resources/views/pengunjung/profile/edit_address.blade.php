@extends('layouts_pengunjung.main')
@section('title', 'Edit Alamat')

@section('content')
    <style>
        #map {
            height: 300px;
            width: 100%;
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="province_id">Provinsi <span class="text-danger">*</span></label>
                                        <select class="form-control @error('province_id') is-invalid @enderror"
                                            id="province_id" name="province_id">
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                        @error('province_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city_id">Kota/Kabupaten <span class="text-danger">*</span></label>
                                        <select class="form-control @error('city_id') is-invalid @enderror" id="city_id"
                                            name="city_id">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kecamatan') is-invalid @enderror"
                                            id="kecamatan" name="kecamatan"
                                            value="{{ old('kecamatan', $address->kecamatan) }}"
                                            placeholder="Masukkan nama kecamatan">
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_pos">Kode Pos</label>
                                        <input type="text" class="form-control @error('kode_pos') is-invalid @enderror"
                                            id="kode_pos" name="kode_pos"
                                            value="{{ old('kode_pos', $address->kode_pos) }}">
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Store province, city names in hidden fields -->
                            <input type="hidden" name="provinsi" id="provinsi" value="{{ $address->provinsi }}">
                            <input type="hidden" name="kota" id="kota" value="{{ $address->kota }}">
                            <input type="hidden" name="district_id" id="district_id" value="{{ $address->district_id }}">

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
    <!-- Include Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Initialize map
        let map;
        let marker;

        document.addEventListener('DOMContentLoaded', function() {
            // Set initial center coordinates based on saved address or fallback to Indonesia
            const savedLat = document.getElementById('latitude').value;
            const savedLng = document.getElementById('longitude').value;
            const initialLat = savedLat || -0.789275;
            const initialLng = savedLng || 113.921327;
            const initialZoom = savedLat && savedLng ? 15 : 5;

            // Initialize map
            map = L.map('map').setView([initialLat, initialLng], initialZoom);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Add marker if coordinates exist
            if (savedLat && savedLng) {
                marker = L.marker([savedLat, savedLng], {
                    draggable: true
                }).addTo(map);

                // Update coordinates when marker is dragged
                marker.on('dragend', updateCoordinates);
            }

            // Handle map click to add/move marker
            map.on('click', function(e) {
                placeMarker(e.latlng);
            });

            // Get current location button
            document.getElementById('getCurrentLocation').addEventListener('click', function() {
                getUserLocation();
            });

            // Load provinces and select the saved one
            loadProvinces().then(() => {
                const provinceId = '{{ $address->province_id }}';
                if (provinceId) {
                    const provinceSelect = document.getElementById('province_id');
                    provinceSelect.value = provinceId;
                    loadCities(provinceId).then(() => {
                        const cityId = '{{ $address->city_id }}';
                        if (cityId) {
                            document.getElementById('city_id').value = cityId;
                        }
                    });
                }
            });

            // Event listeners for dropdowns
            document.getElementById('province_id').addEventListener('change', function() {
                const provinceId = this.value;
                const provinceName = this.options[this.selectedIndex].text;
                document.getElementById('provinsi').value = provinceName;
                loadCities(provinceId);
            });

            document.getElementById('city_id').addEventListener('change', function() {
                const cityId = this.value;
                const cityName = this.options[this.selectedIndex].text;
                document.getElementById('kota').value = cityName;
            });
        });

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const latlng = L.latLng(lat, lng);

                        // Update map view to user's location
                        map.setView(latlng, 15);

                        // Place marker at user's location
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
            // Remove existing marker if any
            if (marker) {
                map.removeLayer(marker);
            }

            // Add new marker at clicked position
            marker = L.marker(latlng, {
                draggable: true
            }).addTo(map);

            // Update form fields with coordinates
            document.getElementById('latitude').value = latlng.lat.toFixed(6);
            document.getElementById('longitude').value = latlng.lng.toFixed(6);

            // Update coordinates when marker is dragged
            marker.on('dragend', updateCoordinates);
        }

        function updateCoordinates() {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(6);
            document.getElementById('longitude').value = position.lng.toFixed(6);
        }

        // Raja Ongkir API integration
        function loadProvinces() {
            return fetch('{{ route('rajaongkir.provinces') }}')
                .then(response => response.json())
                .then(data => {
                    const provinceSelect = document.getElementById('province_id');
                    provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';

                    data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.province_id;
                        option.textContent = province.province;
                        provinceSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading provinces:', error));
        }

        function loadCities(provinceId) {
            if (!provinceId) {
                document.getElementById('city_id').innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                return Promise.resolve();
            }

            return fetch(`{{ route('rajaongkir.cities') }}?province=${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    const citySelect = document.getElementById('city_id');
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';

                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.city_id;
                        option.textContent = city.type + ' ' + city.city_name;
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading cities:', error));
        }
    </script>
@endsection
