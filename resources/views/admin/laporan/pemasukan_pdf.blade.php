<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemasukan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Pemasukan</h2>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_pemasukan as $index => $transaksi)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                <td>{{ $transaksi->kode_transaksi }}</td>
                <td>{{ $transaksi->pelanggan && $transaksi->pelanggan->user ? $transaksi->pelanggan->user->name : '-' }}</td>
                <td class="text-right">Rp {{ number_format($transaksi->sub_total + $transaksi->ongkir, 0, ',', '.') }}</td>
                <td>{{ $transaksi->is_cod ? 'COD' : 'Transfer' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>