@extends('layouts.main')
@section('title', 'Profil Kurir')
@section('page-title', 'Profil Kurir')
@section('page-subtitle', 'Kurir/Kelola-Profil')

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
                        <h4 class="card-title">Edit Profil Kurir</h4>
                        <p class="card-subtitle">Informasi profil kurir yang akan digunakan untuk pengiriman</p>
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

                        <form action="{{ route('kurir.profil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            id="nama" name="nama" value="{{ old('nama', $kurir->user->name) }}"
                                            required>
                                        @error('nama')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telp">Nomor Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('telp') is-invalid @enderror"
                                            id="telp" name="telp" value="{{ old('telp', $kurir->telp) }}" required>
                                        @error('telp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Foto Profil</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input @error('photo') is-invalid @enderror"
                                                    id="photo" name="photo" accept="image/*">
                                                <label class="custom-file-label" for="photo">Pilih file</label>
                                            </div>
                                        </div>
                                        @error('photo')
                                            <div class="text-danger small mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        @if ($kurir->photo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/foto-kurir/' . $kurir->photo) }}"
                                                    alt="Foto Profil" class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <small class="form-text text-muted">
                                            Unggah gambar dengan format JPG, PNG, atau GIF (maks. 2MB)
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label>Status Kurir</label>
                                        <div class="form-control-plaintext">
                                            @if ($kurir->status == 'active')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 d-none">
                                <div class="card-header">
                                    <h5 class="mb-0">Lokasi Kurir</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info mb-2">
                                        <i class="fas fa-info-circle mr-1"></i> Klik pada peta untuk menentukan lokasi kurir
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
                                                    value="{{ old('latitude', $kurir->latitude ?? '-7.8166') }}" readonly>
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
                                                    value="{{ old('longitude', $kurir->longitude ?? '112.0114') }}"
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
                                            <input type="text" class="form-control" id="cari_alamat"
                                                placeholder="Cari alamat atau tempat...">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="tombol_cari">
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        $(document).ready(function() {
            $('.custom-file-input').on('change', function() {
                let nama_file = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(nama_file);
            });

            const lat_awal = parseFloat($('#latitude').val()) || -7.8166;
            const lng_awal = parseFloat($('#longitude').val()) || 112.0114;

            const peta = L.map('map', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([lat_awal, lng_awal], 15);

            const google_streets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google Maps'
            }).addTo(peta);

            const google_sat = L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google Maps Satellite'
            });

            const peta_dasar = {
                "Google Streets": google_streets,
                "Google Satellite": google_sat
            };

            L.control.layers(peta_dasar, null, {
                collapsed: false
            }).addTo(peta);

            const marker = L.marker([lat_awal, lng_awal], {
                draggable: true
            }).addTo(peta);

            marker.on('dragend', function(event) {
                const posisi = marker.getLatLng();
                $('#latitude').val(posisi.lat.toFixed(6));
                $('#longitude').val(posisi.lng.toFixed(6));
            });

            peta.on('click', function(e) {
                marker.setLatLng(e.latlng);
                $('#latitude').val(e.latlng.lat.toFixed(6));
                $('#longitude').val(e.latlng.lng.toFixed(6));
            });

            $('#tombol_cari').on('click', function() {
                const pencarian = $('#cari_alamat').val();
                if (pencarian) {
                    $.get(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(pencarian)}`,
                        function(data) {
                            if (data && data.length > 0) {
                                const lokasi = data[0];
                                const latlng = L.latLng(parseFloat(lokasi.lat), parseFloat(lokasi.lon));

                                marker.setLatLng(latlng);
                                peta.setView(latlng, 16);

                                $('#latitude').val(parseFloat(lokasi.lat).toFixed(6));
                                $('#longitude').val(parseFloat(lokasi.lon).toFixed(6));
                            } else {
                                alert('Lokasi tidak ditemukan. Silakan coba dengan kata kunci lain.');
                            }
                        });
                }
            });

            $('#cari_alamat').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#tombol_cari').click();
                }
            });

            setTimeout(function() {
                peta.invalidateSize();
            }, 100);
        });
    </script>
@endsection
