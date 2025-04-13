<?php
function rupiahFormat($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
function tanggalIndo($tanggal)
{
    return date('d-m-Y', strtotime($tanggal));
}

function tanggalIndoLengkap($tanggal)
{
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    $tanggal = strtotime($tanggal);
    $hari = date('d', $tanggal);
    $bulanIndo = $bulan[date('n', $tanggal)];
    $tahun = date('Y', $tanggal);

    return $hari . ' ' . $bulanIndo . ' ' . $tahun;
}
