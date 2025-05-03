@extends('layouts.main')
@section('title', 'Profil Toko')
@section('page-title', 'Profil Toko')
@section('page-subtitle', 'Master/Profil-Toko')

@section('content')
    <style>
        #map {
            height: 300px;
            width: 100%;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profil Toko</h4>
                        <p class="card-subtitle">Informasi yang akan ditampilkan di halaman frontend</p>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.profile-toko.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_toko">Nama Toko <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_toko') is-invalid @enderror"
                                            id="nama_toko" name="nama_toko"
                                            value="{{ old('nama_toko', $profile->nama_toko) }}" required>
                                        @error('nama_toko')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="logo">Logo Toko</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input @error('logo') is-invalid @enderror"
                                                    id="logo" name="logo" accept="image/*">
                                                <label class="custom-file-label" for="logo">
                                                    Pilih file
                                                </label>
                                            </div>
                                        </div>
                                        @error('logo')
                                            <div class="text-danger small mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        @if ($profile->logo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo Toko"
                                                    class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <small class="form-text text-muted">
                                            Unggah gambar dengan format JPG, PNG, atau GIF (maks. 2MB)
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $profile->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="telepon">Nomor Telepon</label>
                                        <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                            id="telepon" name="telepon" value="{{ old('telepon', $profile->telepon) }}">
                                        @error('telepon')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email Toko</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $profile->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jam_operasional">Jam Operasional</label>
                                        <input type="text"
                                            class="form-control @error('jam_operasional') is-invalid @enderror"
                                            id="jam_operasional" name="jam_operasional"
                                            value="{{ old('jam_operasional', $profile->jam_operasional) }}"
                                            placeholder="Contoh: Senin-Jumat, 09:00 - 17:00">
                                        @error('jam_operasional')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi Toko</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5">{{ old('deskripsi', $profile->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h5 class="mb-0">Media Sosial</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="facebook">Facebook</label>
                                                <input type="text"
                                                    class="form-control @error('facebook') is-invalid @enderror"
                                                    id="facebook" name="facebook"
                                                    value="{{ old('facebook', $profile->facebook) }}"
                                                    placeholder="https://facebook.com/namatoko">
                                                @error('facebook')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="instagram">Instagram</label>
                                                <input type="text"
                                                    class="form-control @error('instagram') is-invalid @enderror"
                                                    id="instagram" name="instagram"
                                                    value="{{ old('instagram', $profile->instagram) }}"
                                                    placeholder="https://instagram.com/namatoko">
                                                @error('instagram')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="twitter">Twitter</label>
                                                <input type="text"
                                                    class="form-control @error('twitter') is-invalid @enderror"
                                                    id="twitter" name="twitter"
                                                    value="{{ old('twitter', $profile->twitter) }}"
                                                    placeholder="https://twitter.com/namatoko">
                                                @error('twitter')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="whatsapp">WhatsApp</label>
                                                <input type="text"
                                                    class="form-control @error('whatsapp') is-invalid @enderror"
                                                    id="whatsapp" name="whatsapp"
                                                    value="{{ old('whatsapp', $profile->whatsapp) }}"
                                                    placeholder="628123456789">
                                                @error('whatsapp')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Masukkan nomor WhatsApp dengan format internasional tanpa tanda +
                                                    (contoh: 628123456789)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Lokasi Toko</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info mb-2">
                                        <i class="fas fa-info-circle mr-1"></i> Klik pada peta untuk menentukan lokasi toko
                                        atau geser marker untuk menyesuaikan posisi.
                                    </div>

                                    <div id="map" class="mb-3"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="latitude">Latitude</label>
                                                <input type="text"
                                                    class="form-control @error('latitude') is-invalid @enderror"
                                                    id="latitude" name="latitude"
                                                    value="{{ old('latitude', $profile->latitude ?? '-7.8166') }}"
                                                    readonly>
                                                @error('latitude')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="longitude">Longitude</label>
                                                <input type="text"
                                                    class="form-control @error('longitude') is-invalid @enderror"
                                                    id="longitude" name="longitude"
                                                    value="{{ old('longitude', $profile->longitude ?? '112.0114') }}"
                                                    readonly>
                                                @error('longitude')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchAddress"
                                                placeholder="Cari alamat atau tempat...">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="searchButton">
                                                    <i class="fas fa-search"></i> Cari
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">
                                            Masukkan alamat atau nama tempat untuk mencari lokasi pada peta
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Leaflet JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        $(document).ready(function() {
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace('deskripsi');
            }

            const initialLat = parseFloat($('#latitude').val()) || -7.8166;
            const initialLng = parseFloat($('#longitude').val()) || 112.0114;


            const map = L.map('map', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([initialLat, initialLng], 15);

            // Google Maps Layer
            const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google Maps'
            }).addTo(map);

            // Google Satellite Layer
            const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google Maps Satellite'
            });

            // Base Maps for Layer Control
            const baseMaps = {
                "Google Streets": googleStreets,
                "Google Satellite": googleSat
            };

            // Add layer control
            L.control.layers(baseMaps, null, {
                collapsed: false
            }).addTo(map);

            // Create marker with custom icon
            const marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            // Update coordinates when marker is dragged
            marker.on('dragend', function(event) {
                const position = marker.getLatLng();
                $('#latitude').val(position.lat.toFixed(6));
                $('#longitude').val(position.lng.toFixed(6));
            });

            // Update marker position when map is clicked
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                $('#latitude').val(e.latlng.lat.toFixed(6));
                $('#longitude').val(e.latlng.lng.toFixed(6));
            });

            // Search functionality using Nominatim
            $('#searchButton').on('click', function() {
                const query = $('#searchAddress').val();
                if (query) {
                    $.get(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`,
                        function(data) {
                            if (data && data.length > 0) {
                                const location = data[0];
                                const latlng = L.latLng(parseFloat(location.lat), parseFloat(location
                                    .lon));

                                marker.setLatLng(latlng);
                                map.setView(latlng, 16);

                                $('#latitude').val(parseFloat(location.lat).toFixed(6));
                                $('#longitude').val(parseFloat(location.lon).toFixed(6));
                            } else {
                                alert('Lokasi tidak ditemukan. Silakan coba dengan kata kunci lain.');
                            }
                        });
                }
            });

            // Search on Enter key press
            $('#searchAddress').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#searchButton').click();
                }
            });

            // Fix map display issues by triggering resize
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        });
    </script>
@endsection
