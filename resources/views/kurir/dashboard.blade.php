@extends('layouts.main')
@section('title', 'Dashboard Kurir')
@section('page-title', 'Dashboard Kurir')
@section('page-subtitle', 'Dashboard/overview')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-2em8A3tkXhldfnYJLGRiG2OPs0o5tUXO+KfLQbhJc8vcNVvxjDmuPLRFlQvX2wh5B3XEu5oXqfV9gH4pG7+S1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Dikirim</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dikirimCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Selesai Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $selesaiCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    COD</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $codCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Pengiriman yang Perlu Diproses</h6>
                        <a href="{{ route('kurir.pesanan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @if ($pendingOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Pelanggan</th>
                                            <th>Alamat</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingOrders as $order)
                                            <tr>
                                                <td>{{ $order->kode_transaksi }}</td>
                                                <td>{{ $order->pelanggan->user->name }}</td>
                                                <td>{{ $order->alamat->alamat }}, {{ $order->alamat->kecamatan }}</td>
                                                <td>
                                                    @if ($order->status_pengiriman == 'Dikirim')
                                                        <span class="badge badge-warning text-white">Dikirim</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('kurir.pesanan.index') }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>Tidak ada pengiriman yang perlu diproses!</h5>
                                <p class="text-muted">Semua pengiriman telah selesai.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Performa Pengiriman</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="deliveryChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-warning"></i> Dikirim
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Selesai
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Pengiriman Selesai Terbaru</h6>
                    </div>
                    <div class="card-body">
                        @if ($completedOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Pelanggan</th>
                                            <th>Alamat</th>
                                            <th>COD</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($completedOrders as $order)
                                            <tr>
                                                <td>{{ $order->kode_transaksi }}</td>
                                                <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $order->pelanggan->user->name }}</td>
                                                <td>{{ $order->alamat->alamat }}, {{ $order->alamat->kecamatan }}</td>
                                                <td>
                                                    @if ($order->is_cod)
                                                        <span class="badge badge-success text-white">Ya</span>
                                                    @else
                                                        <span class="badge badge-secondary text-white">Tidak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('kurir.pesanan.index') }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                <h5>Belum ada pengiriman yang selesai</h5>
                                <p class="text-muted">Pengiriman yang selesai akan ditampilkan di sini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Peta Pengiriman Aktif</h6>
                    </div>
                    <div class="card-body">
                        <div id="delivery-map" style="height: 400px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Delivery Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('deliveryChart').getContext('2d');
            const deliveryChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Dikirim', 'Selesai'],
                    datasets: [{
                        data: [{{ $dikirimCount }}, {{ $selesaiCount }}],
                        backgroundColor: ['#f6c23e', '#1cc88a'],
                        hoverBackgroundColor: ['#dda20a', '#17a673'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutout: '70%',
                },
            });

            initLeafletMap();
        });

        function initLeafletMap() {

            const map = L.map('delivery-map').setView([-7.81761500, 112.01319000], 12);
            L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google Maps'
            }).addTo(map);

            const deliveryLocations = @json($deliveryLocations);

            const deliveryIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            deliveryLocations.forEach(location => {
                if (location.lat && location.lng) {
                    const marker = L.marker([parseFloat(location.lat), parseFloat(location.lng)], {
                        icon: deliveryIcon
                    }).addTo(map);

                    marker.bindPopup(`
                    <div>
                        <h6><strong>${location.kode_transaksi}</strong></h6>
                        <p><strong>Pelanggan:</strong> ${location.customer_name}</p>
                        <p><strong>Alamat:</strong> ${location.address}</p>
                        <p><strong>Status:</strong> ${location.status}</p>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${location.lat},${location.lng}" 
                           target="_blank" class="btn btn-sm btn-primary text-white">Navigasi</a>
                    </div>
                `);
                }
            });

            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        }
    </script>
@endsection
