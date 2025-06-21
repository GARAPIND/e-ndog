<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StokHistory;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Illuminate\Support\Facades\DB;

class MigrateStokHistoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:stok-history 
                            {--dry-run : Jalankan tanpa menyimpan data}
                            {--chunk=1000 : Jumlah data per chunk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasi data dari tabel stok_history ke stok_masuk dan stok_keluar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $chunkSize = (int) $this->option('chunk');

        $this->info('Memulai migrasi data stok history...');

        if ($dryRun) {
            $this->warn('Mode DRY RUN - Data tidak akan disimpan');
        }

        // Hitung total data
        $totalRecords = StokHistory::count();
        $this->info("Total records yang akan dimigrasi: {$totalRecords}");

        if ($totalRecords === 0) {
            $this->info('Tidak ada data untuk dimigrasi.');
            return;
        }

        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();

        $stokMasukCount = 0;
        $stokKeluarCount = 0;
        $errorCount = 0;

        try {
            DB::beginTransaction();

            StokHistory::chunk($chunkSize, function ($histories) use (&$bar, &$stokMasukCount, &$stokKeluarCount, &$errorCount, $dryRun) {
                foreach ($histories as $history) {
                    try {
                        $data = [
                            'produk_id' => $history->produk_id,
                            'jumlah' => abs($history->jumlah), // Selalu positif
                            'stok_sebelum' => $history->stok_sebelum,
                            'stok_setelah' => $history->stok_setelah,
                            'tipe' => $history->tipe,
                            'keterangan' => $history->keterangan,
                            'referensi_id' => $history->referensi_id,
                            'referensi_tipe' => $history->referensi_tipe,
                            'user_id' => $history->user_id,
                            'created_at' => $history->created_at,
                            'updated_at' => $history->updated_at,
                        ];

                        // Tentukan apakah masuk atau keluar berdasarkan tipe dan jumlah
                        $isStokMasuk = $this->isStokMasuk($history->tipe, $history->jumlah);

                        if (!$dryRun) {
                            if ($isStokMasuk) {
                                StokMasuk::create($data);
                                $stokMasukCount++;
                            } else {
                                StokKeluar::create($data);
                                $stokKeluarCount++;
                            }
                        } else {
                            // Untuk dry run, hanya hitung
                            if ($isStokMasuk) {
                                $stokMasukCount++;
                            } else {
                                $stokKeluarCount++;
                            }
                        }
                    } catch (\Exception $e) {
                        $this->error("Error pada ID {$history->id}: " . $e->getMessage());
                        $errorCount++;
                    }

                    $bar->advance();
                }
            });

            if (!$dryRun) {
                DB::commit();
                $this->info("\nMigrasi selesai!");
            } else {
                DB::rollBack();
                $this->info("\nDry run selesai!");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        $bar->finish();

        // Tampilkan ringkasan
        $this->newLine(2);
        $this->info("=== RINGKASAN MIGRASI ===");
        $this->info("Data stok masuk: {$stokMasukCount}");
        $this->info("Data stok keluar: {$stokKeluarCount}");
        $this->info("Error: {$errorCount}");
        $this->info("Total: " . ($stokMasukCount + $stokKeluarCount));

        if ($dryRun) {
            $this->warn("\nUntuk menjalankan migrasi sesungguhnya, jalankan tanpa --dry-run");
        }

        return 0;
    }

    /**
     * Tentukan apakah record adalah stok masuk atau keluar
     */
    private function isStokMasuk($tipe, $jumlah)
    {
        // Berdasarkan tipe
        $tipeMasuk = ['masuk', 'adjustment_tambah', 'return', 'produksi'];
        $tipeKeluar = ['keluar', 'adjustment_kurang', 'penjualan', 'rusak', 'hilang'];

        if (in_array($tipe, $tipeMasuk)) {
            return true;
        }

        if (in_array($tipe, $tipeKeluar)) {
            return false;
        }

        // Jika tipe tidak dikenali, tentukan berdasarkan jumlah
        // Jumlah positif = masuk, negatif = keluar
        return $jumlah >= 0;
    }
}
