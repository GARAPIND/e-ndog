<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
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
    public function get_stok_masuk_data(Request $request)
    {
        $query = StokMasuk::with(['produk', 'user']);

        $this->terapkan_filter_stok($query, $request);

        $dataTable = DataTables::of($query)
            ->addColumn('produk_nama', function ($stok) {
                return $stok->produk ? $stok->produk->nama : '-';
            })
            ->addColumn('user_nama', function ($stok) {
                return $stok->user ? $stok->user->name : 'System';
            })
            ->addColumn('tanggal', function ($stok) {
                return Carbon::parse($stok->created_at)->format('d/m/Y H:i');
            })
            ->addColumn('jumlah_formatted', function ($stok) {
                $berat_produk = $stok->produk ? $stok->produk->berat : 1;
                $total_berat = $stok->jumlah * $berat_produk;
                return  $total_berat . ' kg';
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            });

        if ($request->get('length') == -1) {
            $dataTable = $dataTable->skipPaging();
        }

        return $dataTable->make(true);
    }

    public function get_stok_keluar_data(Request $request)
    {
        $query = StokKeluar::with(['produk', 'user']);

        $this->terapkan_filter_stok($query, $request);

        $dataTable = DataTables::of($query)
            ->addColumn('produk_nama', function ($stok) {
                return $stok->produk ? $stok->produk->nama : '-';
            })
            ->addColumn('user_nama', function ($stok) {
                return $stok->user ? $stok->user->name : 'System';
            })
            ->addColumn('tanggal', function ($stok) {
                return Carbon::parse($stok->created_at)->format('d/m/Y H:i');
            })
            ->addColumn('jumlah_formatted', function ($stok) {
                $berat_produk = $stok->produk ? $stok->produk->berat : 1;
                $total_berat = $stok->jumlah * $berat_produk;
                return  $total_berat . ' kg';
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            });

        if ($request->get('length') == -1) {
            $dataTable = $dataTable->skipPaging();
        }

        return $dataTable->make(true);
    }
    public function get_stok_data(Request $request)
    {
        // Gabungkan query dari stok_masuk dan stok_keluar
        $stokMasuk = StokMasuk::with(['produk', 'user'])
            ->select('*', DB::raw("'masuk' as direction"));

        $stokKeluar = StokKeluar::with(['produk', 'user'])
            ->select('*', DB::raw("'keluar' as direction"));

        // Apply filter ke kedua query
        $this->terapkan_filter_stok($stokMasuk, $request);
        $this->terapkan_filter_stok($stokKeluar, $request);

        // Union kedua query
        $query = $stokMasuk->union($stokKeluar);

        $dataTable = DataTables::of($query)
            ->addColumn('produk_nama', function ($stok) {
                return $stok->produk ? $stok->produk->nama : '-';
            })
            ->addColumn('user_nama', function ($stok) {
                return $stok->user ? $stok->user->name : 'System';
            })
            ->addColumn('tanggal', function ($stok) {
                return Carbon::parse($stok->created_at)->format('d/m/Y H:i');
            })
            ->addColumn('tipe_label', function ($stok) {
                $label_tipe = [
                    'masuk' => '<span class="badge bg-success text-white">Masuk</span>',
                    'keluar' => '<span class="badge bg-danger text-white">Keluar</span>',
                    'adjustment_tambah' => '<span class="badge bg-info text-white">Penyesuaian +</span>',
                    'adjustment_kurang' => '<span class="badge bg-warning text-white">Penyesuaian -</span>',
                    'return' => '<span class="badge bg-primary text-white">Return</span>',
                    'produksi' => '<span class="badge bg-secondary text-white">Produksi</span>',
                    'penjualan' => '<span class="badge bg-danger text-white">Penjualan</span>',
                    'rusak' => '<span class="badge bg-warning text-white">Rusak</span>',
                    'hilang' => '<span class="badge bg-dark text-white">Hilang</span>'
                ];
                return $label_tipe[$stok->tipe] ?? '<span class="badge bg-secondary">' . $stok->tipe . '</span>';
            })
            ->addColumn('jumlah_formatted', function ($stok) {
                // Hitung total berat (jumlah × berat produk)
                $berat_produk = $stok->produk ? $stok->produk->berat : 1;
                $total_berat = $stok->jumlah * $berat_produk;

                if ($stok->direction === 'masuk') {
                    return '<span class="text-success">+' . $total_berat . ' kg</span>';
                } else {
                    return '<span class="text-danger">-' . $total_berat . ' kg</span>';
                }
            })
            ->addColumn('jumlah_unit', function ($stok) {
                // Tampilkan jumlah unit asli
                return $stok->jumlah . ' unit';
            })
            ->addColumn('berat_satuan', function ($stok) {
                // Tampilkan berat per satuan
                $berat_produk = $stok->produk ? $stok->produk->berat : 0;
                return $berat_produk . ' kg/unit';
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
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
        // Query untuk stok masuk dengan kalkulasi berat
        $queryMasuk = StokMasuk::with('produk')
            ->select('stok_masuk.*');
        $this->terapkan_filter_stok($queryMasuk, $request);
        $dataMasuk = $queryMasuk->get();

        $totalBeratMasuk = $dataMasuk->sum(function ($item) {
            $berat_produk = $item->produk ? $item->produk->berat : 1;
            return $item->jumlah * $berat_produk;
        });

        // Query untuk stok keluar dengan kalkulasi berat
        $queryKeluar = StokKeluar::with('produk')
            ->select('stok_keluar.*');
        $this->terapkan_filter_stok($queryKeluar, $request);
        $dataKeluar = $queryKeluar->get();

        $totalBeratKeluar = $dataKeluar->sum(function ($item) {
            $berat_produk = $item->produk ? $item->produk->berat : 1;
            return $item->jumlah * $berat_produk;
        });

        return response()->json([
            'total_masuk' => $totalBeratMasuk . ' kg',
            'total_keluar' => $totalBeratKeluar . ' kg',
            'total_transaksi' => $dataMasuk->count() + $dataKeluar->count(),
            'total_masuk_unit' => $dataMasuk->sum('jumlah'),
            'total_keluar_unit' => $dataKeluar->sum('jumlah'),
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
        // dd($request->all());
        $type = $request->get('type');
        // dd($type);
        // Ambil data dari kedua tabel
        $stokMasuk = StokMasuk::with(['produk', 'user']);
        $stokKeluar = StokKeluar::with(['produk', 'user']);

        $this->terapkan_filter_stok($stokMasuk, $request);
        $this->terapkan_filter_stok($stokKeluar, $request);

        $dataMasuk = $stokMasuk->get()->map(function ($item) {
            $item->direction = 'masuk';
            $berat_produk = $item->produk ? $item->produk->berat : 1;
            $total_berat = $item->jumlah * $berat_produk;
            $item->jumlah_formatted = $total_berat . ' kg';
            $item->total_berat = $total_berat;
            return $item;
        });

        $dataKeluar = $stokKeluar->get()->map(function ($item) {
            $item->direction = 'keluar';
            $berat_produk = $item->produk ? $item->produk->berat : 1;
            $total_berat = $item->jumlah * $berat_produk;
            $item->jumlah_formatted = $total_berat . ' kg';
            $item->total_berat = $total_berat;
            return $item;
        });
        if ($format === 'masuk') {
            $data_stok = $dataMasuk->sortByDesc('created_at');
        } else if ($format === 'keluar') {
            $data_stok = $dataKeluar->sortByDesc('created_at');
        } else {
            $data_stok = $dataMasuk->concat($dataKeluar)->sortByDesc('created_at');
        }

        $tanggal_cetak = now()->format('d/m/Y H:i:s');

        if ($format === 'excel') {
            return Excel::download(new LaporanStokExport($data_stok), 'laporan_stok_' . date('Y-m-d') . '.xlsx');
        } else {
            $pdf = Pdf::loadView('admin.laporan.stok_pdf', compact('data_stok', 'tanggal_cetak', 'format'));
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
            // Data stok masuk dengan kalkulasi berat
            $dataMasuk = StokMasuk::with('produk')
                ->select('stok_masuk.*', DB::raw('MONTH(created_at) as bulan'))
                ->whereYear('created_at', $tahun)
                ->get()
                ->groupBy('bulan')
                ->map(function ($items, $bulan) {
                    $total_berat = $items->sum(function ($item) {
                        $berat_produk = $item->produk ? $item->produk->berat : 1;
                        return $item->jumlah * $berat_produk;
                    });
                    return (object)[
                        'bulan' => $bulan,
                        'total_masuk' => $total_berat
                    ];
                });

            // Data stok keluar dengan kalkulasi berat
            $dataKeluar = StokKeluar::with('produk')
                ->select('stok_keluar.*', DB::raw('MONTH(created_at) as bulan'))
                ->whereYear('created_at', $tahun)
                ->get()
                ->groupBy('bulan')
                ->map(function ($items, $bulan) {
                    $total_berat = $items->sum(function ($item) {
                        $berat_produk = $item->produk ? $item->produk->berat : 1;
                        return $item->jumlah * $berat_produk;
                    });
                    return (object)[
                        'bulan' => $bulan,
                        'total_keluar' => $total_berat
                    ];
                });

            $chart_data = [];
            for ($i = 1; $i <= 12; $i++) {
                $masuk_data = $dataMasuk->get($i);
                $keluar_data = $dataKeluar->get($i);

                $chart_data[] = [
                    'label' => Carbon::create()->month($i)->translatedFormat('M'),
                    'masuk' => $masuk_data ? $masuk_data->total_masuk : 0,
                    'keluar' => $keluar_data ? $keluar_data->total_keluar : 0,
                ];
            }
        } else {
            // Data tahunan dengan kalkulasi berat
            $dataMasuk = StokMasuk::with('produk')
                ->select('stok_masuk.*', DB::raw('YEAR(created_at) as tahun'))
                ->get()
                ->groupBy('tahun')
                ->map(function ($items, $tahun) {
                    $total_berat = $items->sum(function ($item) {
                        $berat_produk = $item->produk ? $item->produk->berat : 1;
                        return $item->jumlah * $berat_produk;
                    });
                    return (object)[
                        'tahun' => $tahun,
                        'total_masuk' => $total_berat
                    ];
                });

            $dataKeluar = StokKeluar::with('produk')
                ->select('stok_keluar.*', DB::raw('YEAR(created_at) as tahun'))
                ->get()
                ->groupBy('tahun')
                ->map(function ($items, $tahun) {
                    $total_berat = $items->sum(function ($item) {
                        $berat_produk = $item->produk ? $item->produk->berat : 1;
                        return $item->jumlah * $berat_produk;
                    });
                    return (object)[
                        'tahun' => $tahun,
                        'total_keluar' => $total_berat
                    ];
                });

            // Gabungkan data berdasarkan tahun
            $tahunList = $dataMasuk->keys()->merge($dataKeluar->keys())->unique()->sort();

            $chart_data = $tahunList->map(function ($tahun) use ($dataMasuk, $dataKeluar) {
                $masuk = $dataMasuk->get($tahun);
                $keluar = $dataKeluar->get($tahun);

                return [
                    'label' => $tahun,
                    'masuk' => $masuk ? $masuk->total_masuk : 0,
                    'keluar' => $keluar ? $keluar->total_keluar : 0,
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
