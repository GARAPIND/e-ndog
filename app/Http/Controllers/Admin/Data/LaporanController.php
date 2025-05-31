<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\StokHistory;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanStokExport;
use App\Exports\LaporanPemasukanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function get_stok_data(Request $request)
    {
        $query = StokHistory::with(['produk', 'user'])
            ->select('stok_history.*');

        $this->terapkan_filter_stok($query, $request);

        $dataTable = DataTables::of($query)
            ->addColumn('produk_nama', function ($stok) {
                return $stok->produk ? $stok->produk->nama : '-';
            })
            ->addColumn('user_nama', function ($stok) {
                return $stok->user ? $stok->user->name : 'System';
            })
            ->addColumn('tanggal', function ($stok) {
                return $stok->created_at->format('d/m/Y H:i');
            })
            ->addColumn('tipe_label', function ($stok) {
                $label_tipe = [
                    'masuk' => '<span class="badge bg-success text-white">Masuk</span>',
                    'keluar' => '<span class="badge bg-danger text-white">Keluar</span>',
                    'adjustment_tambah' => '<span class="badge bg-info text-white">Penyesuaian +</span>',
                    'adjustment_kurang' => '<span class="badge bg-warning text-white">Penyesuaian -</span>'
                ];
                return $label_tipe[$stok->tipe] ?? '<span class="badge bg-secondary">' . $stok->tipe . '</span>';
            })
            ->addColumn('jumlah_formatted', function ($stok) {
                $kelas = $stok->jumlah > 0 ? 'text-success' : 'text-danger';
                $tanda = $stok->jumlah > 0 ? '+' : '';
                return '<span class="' . $kelas . '">' . $tanda . $stok->jumlah . '</span>';
            })
            ->rawColumns(['tipe_label', 'jumlah_formatted']);


        if ($request->get('length') == -1) {
            $dataTable = $dataTable->skipPaging();
        }

        return $dataTable->make(true);
    }

    public function get_pemasukan_data(Request $request)
    {
        $query = Transaksi::with(['pelanggan.user'])
            ->where('status_pembayaran', 'Sudah Dibayar')
            ->select('transaksi.*');

        $this->terapkan_filter_pemasukan($query, $request);

        $dataTable = DataTables::of($query)
            ->addColumn('pelanggan_nama', function ($transaksi) {
                return $transaksi->pelanggan && $transaksi->pelanggan->user ?
                    $transaksi->pelanggan->user->name : '-';
            })
            ->addColumn('tanggal_formatted', function ($transaksi) {
                return Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y');
            })
            ->addColumn('total_formatted', function ($transaksi) {
                $total = $transaksi->sub_total + $transaksi->ongkir;
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('cod_label', function ($transaksi) {
                return $transaksi->is_cod ?
                    '<span class="badge bg-success text-white">COD</span>' :
                    '<span class="badge bg-primary text-white">Transfer</span>';
            })
            ->rawColumns(['cod_label']);

        if ($request->get('length') == -1) {
            $dataTable = $dataTable->skipPaging();
        }

        return $dataTable->make(true);
    }

    public function get_stok_summary(Request $request)
    {
        $query = StokHistory::select(
            DB::raw('SUM(CASE WHEN jumlah > 0 THEN jumlah ELSE 0 END) as total_masuk'),
            DB::raw('SUM(CASE WHEN jumlah < 0 THEN ABS(jumlah) ELSE 0 END) as total_keluar'),
            DB::raw('COUNT(*) as total_transaksi')
        );

        $this->terapkan_filter_stok($query, $request);

        $hasil = $query->first();

        return response()->json([
            'total_masuk' => $hasil->total_masuk ?? 0,
            'total_keluar' => $hasil->total_keluar ?? 0,
            'total_transaksi' => $hasil->total_transaksi ?? 0,
        ]);
    }

    public function get_pemasukan_summary(Request $request)
    {
        $query = Transaksi::where('status_pembayaran', 'Sudah Dibayar')
            ->select(
                DB::raw('SUM(sub_total + ongkir) as total_pemasukan'),
                DB::raw('SUM(CASE WHEN is_cod = 1 THEN sub_total + ongkir ELSE 0 END) as pemasukan_cod'),
                DB::raw('SUM(CASE WHEN is_cod = 0 THEN sub_total + ongkir ELSE 0 END) as pemasukan_transfer')
            );

        $this->terapkan_filter_pemasukan($query, $request);

        $hasil = $query->first();

        return response()->json([
            'total_pemasukan' => $hasil->total_pemasukan ?? 0,
            'pemasukan_cod' => $hasil->pemasukan_cod ?? 0,
            'pemasukan_transfer' => $hasil->pemasukan_transfer ?? 0,
        ]);
    }

    public function unduh_laporan_stok(Request $request)
    {
        $format = $request->get('format', 'excel');
        $query = StokHistory::with(['produk', 'user']);

        $this->terapkan_filter_stok($query, $request);

        $data_stok = $query->orderBy('created_at', 'desc')->get();
        $tanggal_cetak = now()->format('d/m/Y H:i:s');
        if ($format === 'excel') {
            return Excel::download(new LaporanStokExport($data_stok), 'laporan_stok_' . date('Y-m-d') . '.xlsx');
        } else {
            $pdf = Pdf::loadView('admin.laporan.stok_pdf', compact('data_stok', 'tanggal_cetak'));
            return $pdf->download('laporan_stok_' . date('Y-m-d') . '.pdf');
        }
    }

    public function unduh_laporan_pemasukan(Request $request)
    {
        $format = $request->get('format', 'excel');
        $query = Transaksi::with(['pelanggan.user'])
            ->where('status_pembayaran', 'Sudah Dibayar');

        $this->terapkan_filter_pemasukan($query, $request);

        $data_pemasukan = $query->orderBy('tanggal_transaksi', 'desc')->get();
        $tanggal_cetak = now()->format('d/m/Y H:i:s');
        if ($format === 'excel') {
            return Excel::download(new LaporanPemasukanExport($data_pemasukan), 'laporan_pemasukan_' . date('Y-m-d') . '.xlsx');
        } else {
            $pdf = Pdf::loadView('admin.laporan.pemasukan_pdf', compact('data_pemasukan', 'tanggal_cetak'));
            return $pdf->download('laporan_pemasukan_' . date('Y-m-d') . '.pdf');
        }
    }

    private function terapkan_filter_stok($query, Request $request)
    {
        $filter_type = $request->get('filter_type');

        if ($filter_type === 'hari') {
            $tanggal = $request->get('filter_value');
            if ($tanggal) {
                $query->whereDate('created_at', $tanggal);
            }
        } elseif ($filter_type === 'minggu') {
            $minggu = $request->get('minggu');
            $bulan = $request->get('bulan');
            $tahun = $request->get('tahun');

            if ($minggu && $bulan && $tahun) {
                $tanggal_mulai = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $minggu_ke = (int)$minggu;

                $tanggal_awal = $tanggal_mulai->copy()->addWeeks($minggu_ke - 1)->startOfWeek();
                $tanggal_akhir = $tanggal_awal->copy()->endOfWeek();

                $query->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
            }
        } elseif ($filter_type === 'bulan') {
            $bulan = $request->get('bulan');
            $tahun = $request->get('tahun');

            if ($bulan && $tahun) {
                $query->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
            }
        } elseif ($filter_type === 'tahun') {
            $tahun = $request->get('tahun');
            if ($tahun) {
                $query->whereYear('created_at', $tahun);
            }
        }
    }

    private function terapkan_filter_pemasukan($query, Request $request)
    {
        $filter_type = $request->get('filter_type');

        if ($filter_type === 'hari') {
            $tanggal = $request->get('filter_value');
            if ($tanggal) {
                $query->whereDate('tanggal_transaksi', $tanggal);
            }
        } elseif ($filter_type === 'minggu') {
            $minggu = $request->get('minggu');
            $bulan = $request->get('bulan');
            $tahun = $request->get('tahun');

            if ($minggu && $bulan && $tahun) {
                $tanggal_mulai = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $minggu_ke = (int)$minggu;

                $tanggal_awal = $tanggal_mulai->copy()->addWeeks($minggu_ke - 1)->startOfWeek();
                $tanggal_akhir = $tanggal_awal->copy()->endOfWeek();

                $query->whereBetween('tanggal_transaksi', [$tanggal_awal, $tanggal_akhir]);
            }
        } elseif ($filter_type === 'bulan') {
            $bulan = $request->get('bulan');
            $tahun = $request->get('tahun');

            if ($bulan && $tahun) {
                $query->whereMonth('tanggal_transaksi', $bulan)
                    ->whereYear('tanggal_transaksi', $tahun);
            }
        } elseif ($filter_type === 'tahun') {
            $tahun = $request->get('tahun');
            if ($tahun) {
                $query->whereYear('tanggal_transaksi', $tahun);
            }
        }
    }

    public function get_chart_data_stok(Request $request)
    {
        $periode = $request->get('periode', 'bulan');
        $tahun = $request->get('tahun', date('Y'));

        if ($periode === 'bulan') {
            $data = StokHistory::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(CASE WHEN jumlah > 0 THEN jumlah ELSE 0 END) as total_masuk'),
                DB::raw('SUM(CASE WHEN jumlah < 0 THEN ABS(jumlah) ELSE 0 END) as total_keluar')
            )
                ->whereYear('created_at', $tahun)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('bulan')
                ->get();

            $chart_data = [];
            for ($i = 1; $i <= 12; $i++) {
                $bulan_data = $data->where('bulan', $i)->first();
                $chart_data[] = [
                    'label' => Carbon::create()->month($i)->translatedFormat('M'),
                    'masuk' => $bulan_data ? $bulan_data->total_masuk : 0,
                    'keluar' => $bulan_data ? $bulan_data->total_keluar : 0,
                ];
            }
        } else {
            $data = StokHistory::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('SUM(CASE WHEN jumlah > 0 THEN jumlah ELSE 0 END) as total_masuk'),
                DB::raw('SUM(CASE WHEN jumlah < 0 THEN ABS(jumlah) ELSE 0 END) as total_keluar')
            )
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->orderBy('tahun')
                ->get();

            $chart_data = $data->map(function ($item) {
                return [
                    'label' => $item->tahun,
                    'masuk' => $item->total_masuk,
                    'keluar' => $item->total_keluar,
                ];
            });
        }

        return response()->json($chart_data);
    }

    public function get_chart_data_pemasukan(Request $request)
    {
        $periode = $request->get('periode', 'bulan');
        $tahun = $request->get('tahun', date('Y'));

        if ($periode === 'bulan') {
            $data = Transaksi::select(
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('SUM(sub_total + ongkir) as total_pemasukan'),
                DB::raw('SUM(CASE WHEN is_cod = 1 THEN sub_total + ongkir ELSE 0 END) as pemasukan_cod'),
                DB::raw('SUM(CASE WHEN is_cod = 0 THEN sub_total + ongkir ELSE 0 END) as pemasukan_transfer')
            )
                ->where('status_pembayaran', 'Sudah Dibayar')
                ->whereYear('tanggal_transaksi', $tahun)
                ->groupBy(DB::raw('MONTH(tanggal_transaksi)'))
                ->orderBy('bulan')
                ->get();

            $chart_data = [];
            for ($i = 1; $i <= 12; $i++) {
                $bulan_data = $data->where('bulan', $i)->first();
                $chart_data[] = [
                    'label' => Carbon::create()->month($i)->translatedFormat('M'),
                    'total' => $bulan_data ? $bulan_data->total_pemasukan : 0,
                    'cod' => $bulan_data ? $bulan_data->pemasukan_cod : 0,
                    'transfer' => $bulan_data ? $bulan_data->pemasukan_transfer : 0,
                ];
            }
        } else {
            $data = Transaksi::select(
                DB::raw('YEAR(tanggal_transaksi) as tahun'),
                DB::raw('SUM(sub_total + ongkir) as total_pemasukan'),
                DB::raw('SUM(CASE WHEN is_cod = 1 THEN sub_total + ongkir ELSE 0 END) as pemasukan_cod'),
                DB::raw('SUM(CASE WHEN is_cod = 0 THEN sub_total + ongkir ELSE 0 END) as pemasukan_transfer')
            )
                ->where('status_pembayaran', 'Sudah Dibayar')
                ->groupBy(DB::raw('YEAR(tanggal_transaksi)'))
                ->orderBy('tahun')
                ->get();

            $chart_data = $data->map(function ($item) {
                return [
                    'label' => $item->tahun,
                    'total' => $item->total_pemasukan,
                    'cod' => $item->pemasukan_cod,
                    'transfer' => $item->pemasukan_transfer,
                ];
            });
        }

        return response()->json($chart_data);
    }
}
