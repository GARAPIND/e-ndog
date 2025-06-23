{{-- @dd($data, $profile); --}}
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
            Pembeli: <strong>{{ $data['pelanggan']['user']['name'] }}</strong><br>
            Tanggal Pembelian: <strong>{{ tanggalIndoLengkap($data['tanggal_transaksi']) }}</strong><br>
            No. Telepon: <strong>{{ $data['pelanggan']['user']['name'] }}
                ({{ $data['pelanggan']['telp'] }})</strong><br>
            Alamat Pengiriman: <br>
            <strong>{{ $data['alamat']['alamat'] }}<br>
                {{ $data['alamat']['kecamatan'] }}, {{ $data['alamat']['kota'] }},
                {{ $data['alamat']['kode_pos'] }}<br>
                {{ $data['alamat']['provinsi'] }}</strong>
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
        <tr>
            <td class="label">Total Ongkos Kirim ({{ $data['detail']->sum('berat') }} kg):</td>
            <td class="text-right">Rp{{ number_format($data['ongkir'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label"><strong>TOTAL BELANJA:</strong></td>
            <td class="text-right">
                <strong>Rp{{ number_format($data['ongkir'] + $data['sub_total'], 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <div class="footer">
        <table>
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
                <td>Status Pembayaran:</td>
                <td>{{ $data['status_pembayaran'] }}</td>
            </tr>
        </table>
    </div>

</body>

</html>
