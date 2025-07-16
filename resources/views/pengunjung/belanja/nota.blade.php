<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $profile['nama_toko'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            color: #ffc800;
            margin: 0;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info small {
            color: gray;
        }

        .section {
            margin-top: 20px;
        }

        .section .title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .section .content {
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .highlight {
            color: black;
            font-weight: bold;
        }

        .totals {
            margin-top: 20px;
            width: 100%;
        }

        .totals td {
            padding: 5px;
        }

        .totals .label {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
        }

        .footer td {
            padding: 3px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div style="display: flex; align-items: center;">
            <img src="{{ public_path('assets/images/logo-icon.png') }}" alt="Logo"
                style="height: 80px; margin-right: 10px;">
        </div>
        <div class="invoice-info">
            <strong>INVOICE</strong><br>
            <small>{{ $data['kode_transaksi'] }}</small>
        </div>
    </div>

    <div class="section">
        <div class="title">DITERBITKAN ATAS NAMA</div>
        <div class="content">
            Penjual: <strong>{{ $profile['nama_toko'] }}</strong>
        </div>
    </div>

    <div class="section">
        <div class="title">UNTUK</div>
        <div class="content">
            @if ($data['is_onsite'])
                {{-- Data untuk pelanggan onsite --}}
                Pembeli: <strong>{{ $data['nama_pelanggan_onsite'] }}</strong><br>
                Tanggal Pembelian: <strong>{{ tanggalIndoLengkap($data['tanggal_transaksi']) }}</strong><br>
                No. Telepon: <strong>{{ $data['no_telepon_onsite'] }}</strong><br>
                Alamat: <br>
                <strong>{{ $data['alamat_onsite'] }}</strong><br>
                <span
                    style="background-color: #28a745; color: white; padding: 2px 5px; border-radius: 3px; font-size: 10px;">ONSITE</span>
            @else
                {{-- Data untuk pelanggan online --}}
                Pembeli: <strong>{{ $data['pelanggan']['user']['name'] }}</strong><br>
                Tanggal Pembelian: <strong>{{ tanggalIndoLengkap($data['tanggal_transaksi']) }}</strong><br>
                No. Telepon: <strong>{{ $data['pelanggan']['user']['name'] }}
                    ({{ $data['pelanggan']['telp'] }})</strong><br>
                Alamat Pengiriman: <br>
                <strong>{{ $data['alamat']['alamat'] }}<br>
                    {{ $data['alamat']['kecamatan'] }}, {{ $data['alamat']['kota'] }},
                    {{ $data['alamat']['kode_pos'] }}<br>
                    {{ $data['alamat']['provinsi'] }}</strong>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>INFO PRODUK</th>
                <th class="text-right">JUMLAH</th>
                <th class="text-right">HARGA SATUAN</th>
                <th class="text-right">TOTAL HARGA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['detail'] as $item)
                <tr>
                    <td>
                        <span class="highlight">{{ $item['produk']['nama'] }}</span><br>
                        Berat: {{ $item['produk']['berat'] }} {{ $item['produk']['satuan'] }}<br>
                    </td>
                    <td class="text-right">{{ $item['jumlah'] }}</td>
                    <td class="text-right">Rp{{ number_format($item['produk']['harga'], 0, ',', '.') }}</td>
                    <td class="text-right">
                        Rp{{ number_format($item['sub_total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="label">TOTAL HARGA ({{ count($data['detail']) }}):</td>
            <td class="text-right">Rp{{ number_format($data['sub_total'], 0, ',', '.') }}</td>
        </tr>
        @if (!$data['is_onsite'])
            <tr>
                <td class="label">Total Ongkos Kirim ({{ $data['detail']->sum('berat') }} kg):</td>
                <td class="text-right">Rp{{ number_format($data['ongkir'], 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td class="label"><strong>TOTAL BELANJA:</strong></td>
            <td class="text-right">
                <strong>Rp{{ number_format($data['ongkir'] + $data['sub_total'], 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <div class="footer">
        <table>
            @if (!$data['is_onsite'])
                <tr>
                    <td>Kurir:</td>
                    <td>
                        @if (is_null($data['kurir_id']))
                            {{ $data['ekspedisi'] }}
                        @else
                            {{ $data['kurir']['user']['name'] }} (Kurir Toko)
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Estimasi Waktu:</td>
                    <td>{{ $data['estimasi_waktu'] }}</td>
                </tr>
                <tr>
                    <td>Tanggal Sampai:</td>
                    <td>{{ $data['tanggal_sampai'] ? tanggalIndoLengkap($data['tanggal_sampai']) : '-' }}</td>
                </tr>
            @endif
            <tr>
                <td>Status Pembayaran:</td>
                <td>{{ $data['status_pembayaran'] }}</td>
            </tr>
            <tr>
                <td>Jenis Transaksi:</td>
                <td>
                    @if ($data['is_onsite'])
                        <span
                            style="background-color: #28a745; color: white; padding: 2px 5px; border-radius: 3px; font-size: 10px;">ONSITE</span>
                    @else
                        @if ($data['is_cod'])
                            <span
                                style="background-color: #ffc107; color: black; padding: 2px 5px; border-radius: 3px; font-size: 10px;">COD</span>
                        @else
                            <span
                                style="background-color: #17a2b8; color: white; padding: 2px 5px; border-radius: 3px; font-size: 10px;">TRANSFER</span>
                        @endif
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
