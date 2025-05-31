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

class LaporanStokExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data_stok;

    public function __construct($data_stok)
    {
        $this->data_stok = $data_stok;
    }

    public function collection()
    {
        return $this->data_stok;
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Produk', 'Tipe', 'Jumlah', 'Keterangan', 'User'];
    }

    public function map($stok): array
    {
        static $no = 1;

        return [$no++, $stok->created_at->format('d/m/Y H:i'), $stok->produk ? $stok->produk->nama : '-', $this->get_tipe_label($stok->tipe), $stok->jumlah, $stok->keterangan, $stok->user ? $stok->user->name : 'System'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE0E0E0',
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'A:G' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Stok';
    }

    private function get_tipe_label($tipe)
    {
        $labels = [
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            'adjustment_tambah' => 'Penyesuaian +',
            'adjustment_kurang' => 'Penyesuaian -',
        ];

        return $labels[$tipe] ?? ucfirst($tipe);
    }
}
