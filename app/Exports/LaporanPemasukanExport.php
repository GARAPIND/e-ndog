<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class LaporanPemasukanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data_pemasukan;
    private $counter = 0;

    public function __construct($data_pemasukan)
    {
        $this->data_pemasukan = $data_pemasukan;
    }

    public function collection()
    {
        return $this->data_pemasukan;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kode Transaksi',
            'Pelanggan',
            'Sub Total',
            'Ongkir',
            'Total',
            'Metode Pembayaran'
        ];
    }

    public function map($transaksi): array
    {
        $this->counter++;

        try {
            // Pastikan tanggal valid
            // dd(1);
            $tanggal = '';
            if ($transaksi->tanggal_transaksi) {
                try {
                    $tanggal = Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y');
                } catch (\Exception $e) {
                    $tanggal = '-';
                }
            }

            // Pastikan nilai numerik tidak null
            $sub_total = is_numeric($transaksi->sub_total) ? (float)$transaksi->sub_total : 0;
            $ongkir = is_numeric($transaksi->ongkir) ? (float)$transaksi->ongkir : 0;
            $total = $sub_total + $ongkir;

            // Pastikan kode transaksi tidak null
            $kode_transaksi = $transaksi->kode_transaksi ?? '-';
            // dd($transaksi);
            // Pastikan nama pelanggan tidak null
            $nama_pelanggan = '-';
            if ($transaksi->pelanggan && $transaksi->pelanggan->user && $transaksi->pelanggan->user->name) {
                $nama_pelanggan = $transaksi->pelanggan->user->name;
            }
            // dd($transaksi->is_cod);
            // Pastikan metode pembayaran tidak null
            if ($transaksi->is_cod === 1) {
                $metode_pembayaran = 'COD'; // Default ke 0 jika null
            } else {
                $metode_pembayaran = 'TRANSFER'; // Default ke 1 jika null
            }

            // dd($transaksi);
            return [
                $this->counter,
                $tanggal,
                $kode_transaksi,
                $nama_pelanggan,
                $sub_total,
                $ongkir,
                $total,
                $metode_pembayaran
            ];
        } catch (\Exception $e) {
            dd($e);
            // Jika ada error, return data default
            return [
                $this->counter,
                '-',
                '-',
                '-',
                0,
                0,
                0,
                'Transfer'
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        try {
            return [
                // Style untuk header
                1 => [
                    'font' => [
                        'bold' => true,
                        'size' => 12
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFE0E0E0',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ],
                // Border untuk semua cell
                'A:H' => [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ],
                // Format number untuk kolom E, F, G (Sub Total, Ongkir, Total)
                'E:G' => [
                    'numberFormat' => [
                        'formatCode' => '#,##0'
                    ]
                ]
            ];
        } catch (\Exception $e) {
            // Return basic style jika ada error
            return [
                1 => [
                    'font' => [
                        'bold' => true,
                    ],
                ],
            ];
        }
    }

    public function title(): string
    {
        return 'Laporan Pemasukan';
    }
}
