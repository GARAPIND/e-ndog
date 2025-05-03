@extends('layouts_pengunjung.main')
@section('title', 'Tambah Alamat Baru')

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
                    <span class="breadcrumb-item active">Tambah Alamat</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-dark mb-0">Tambah Alamat Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.addresses.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="keterangan">Label Alamat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan" name="keterangan" value="{{ old('keterangan') }}"
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
                                    placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
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
                                            id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}"
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
                                            id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}">
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="provinsi" id="provinsi">
                            <input type="hidden" name="kota" id="kota">
                            <input type="hidden" name="district_id" id="district_id" value="0">

                            <div class="card mb-3 mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Lokasi di Peta</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Sistem akan mencoba mendapatkan lokasi Anda saat
                                        ini. Anda juga dapat mengklik pada peta untuk menandai lokasi.
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
                                                    name="latitude" value="{{ old('latitude') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="longitude">Longitude</label>
                                                <input type="text" class="form-control" id="longitude"
                                                    name="longitude" value="{{ old('longitude') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Alamat
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

        document.addEventListener('DOMContentLoaded', function() {
            const initialLat = -0.789275;
            const initialLng = 113.921327;
            const initialZoom = 5;

            map = L.map('map').setView([initialLat, initialLng], initialZoom);

            const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google Maps'
            }).addTo(map);

            const savedLat = document.getElementById('latitude').value;
            const savedLng = document.getElementById('longitude').value;

            if (savedLat && savedLng) {
                marker = L.marker([savedLat, savedLng], {
                    draggable: true
                }).addTo(map);
                map.setView([savedLat, savedLng], 15);

                marker.on('dragend', updateCoordinates);
            } else {
                getUserLocation();
            }

            map.on('click', function(e) {
                placeMarker(e.latlng);
            });

            document.getElementById('getCurrentLocation').addEventListener('click', function() {
                getUserLocation();
            });

            loadProvinces();

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

        function loadProvinces() {
            fetch('{{ route('rajaongkir.provinces') }}')
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
                return;
            }

            fetch(`{{ route('rajaongkir.cities') }}?province=${provinceId}`)
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
