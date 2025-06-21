<!DOCTYPE html>
<html>

<head>
    <title>Laporan Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="header">
        @if ($format === 'masuk')
            <h2>Laporan Stok Masuk</h2>
        @elseif ($format === 'keluar')
            <h2>Laporan Stok Keluar</h2>
        @else
            <h2>Laporan Stok Masuk & Keluar</h2>
        @endif
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                @if ($format === 'masuk')
                    <th>Admin</th>
                @elseif ($format === 'keluar')
                    <th>Pembeli</th>
                @else
                    <th>User</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($data_stok as $index => $stok)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $stok->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $stok->produk ? $stok->produk->nama : '-' }}</td>
                    <td>{{ ucfirst($stok->tipe) }}</td>
                    <td>{{ $stok->jumlah * $stok->produk->berat }} kg</td>
                    <td>{{ $stok->keterangan }}</td>
                    <td>{{ $stok->user ? $stok->user->name : 'System' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
