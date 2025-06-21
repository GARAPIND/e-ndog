<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = [
        'id',
        'nama',
        'kode',
        'deskripsi',
        'harga',
        'harga_grosir',
        'harga_pengampu',
        'stok',
        'stok_minimum',
        'notifikasi_stok',
        'berat',
        'satuan',
        'foto',
        'aktif',
        'kategori_id'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'notifikasi_stok' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }

    public function getHargaAktifAttribute()
    {
        return $this->harga_diskon ?? $this->harga;
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/foto-produk/' . $this->foto);
        }
        return asset('images/default-product.png');
    }

    // Relasi ke stok masuk dan keluar
    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class);
    }

    public function stokKeluar()
    {
        return $this->hasMany(StokKeluar::class);
    }

    // Backward compatibility - gabungan stok masuk dan keluar
    public function stokHistories()
    {
        $stokMasuk = $this->stokMasuk()->get()->map(function ($item) {
            $item->direction = 'masuk';
            return $item;
        });

        $stokKeluar = $this->stokKeluar()->get()->map(function ($item) {
            $item->direction = 'keluar';
            return $item;
        });

        return $stokMasuk->concat($stokKeluar)->sortBy('created_at');
    }

    // Metode untuk menambah stok
    public function tambahStok($jumlah, $keterangan = null, $referensi_id = null, $referensi_tipe = null, $tipe = 'masuk')
    {
        return $this->updateStokMasuk($jumlah, $tipe, $keterangan, $referensi_id, $referensi_tipe);
    }

    // Metode untuk mengurangi stok
    public function kurangiStok($jumlah, $keterangan = null, $referensi_id = null, $referensi_tipe = null, $tipe = 'keluar')
    {
        return $this->updateStokKeluar($jumlah, $tipe, $keterangan, $referensi_id, $referensi_tipe);
    }

    // Metode untuk menyesuaikan stok
    public function sesuaikanStok($jumlah_baru, $keterangan = null)
    {
        $perubahan = $jumlah_baru - $this->stok;

        if ($perubahan > 0) {
            // Tambah stok
            return $this->updateStokMasuk($perubahan, 'adjustment_tambah', $keterangan);
        } elseif ($perubahan < 0) {
            // Kurangi stok
            return $this->updateStokKeluar(abs($perubahan), 'adjustment_kurang', $keterangan);
        }

        return true; // Tidak ada perubahan
    }

    // Metode untuk update stok masuk
    private function updateStokMasuk($jumlah, $tipe, $keterangan = null, $referensi_id = null, $referensi_tipe = null)
    {
        $stok_sebelum = $this->stok;
        $stok_setelah = $stok_sebelum + $jumlah;

        $this->stok = $stok_setelah;
        $saved = $this->save();

        if ($saved) {
            // Buat catatan di stok masuk
            StokMasuk::create([
                'produk_id' => $this->id,
                'jumlah' => $jumlah,
                'stok_sebelum' => $stok_sebelum,
                'stok_setelah' => $stok_setelah,
                'tipe' => $tipe,
                'keterangan' => $keterangan,
                'referensi_id' => $referensi_id,
                'referensi_tipe' => $referensi_tipe,
                'user_id' => auth()->id()
            ]);

            return true;
        }

        return false;
    }

    // Metode untuk update stok keluar
    private function updateStokKeluar($jumlah, $tipe, $keterangan = null, $referensi_id = null, $referensi_tipe = null)
    {
        $stok_sebelum = $this->stok;
        $stok_setelah = $stok_sebelum - $jumlah;

        if ($stok_setelah < 0) {
            return false; // Stok tidak mencukupi
        }

        $this->stok = $stok_setelah;
        $saved = $this->save();

        if ($saved) {
            // Buat catatan di stok keluar
            StokKeluar::create([
                'produk_id' => $this->id,
                'jumlah' => $jumlah,
                'stok_sebelum' => $stok_sebelum,
                'stok_setelah' => $stok_setelah,
                'tipe' => $tipe,
                'keterangan' => $keterangan,
                'referensi_id' => $referensi_id,
                'referensi_tipe' => $referensi_tipe,
                'user_id' => auth()->id()
            ]);

            return true;
        }

        return false;
    }

    // Cek jika stok di bawah minimum
    public function stokMinimum()
    {
        return $this->stok <= $this->stok_minimum;
    }

    // Method untuk mendapatkan total stok masuk
    public function getTotalStokMasuk($startDate = null, $endDate = null)
    {
        $query = $this->stokMasuk();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->sum('jumlah');
    }

    // Method untuk mendapatkan total stok keluar
    public function getTotalStokKeluar($startDate = null, $endDate = null)
    {
        $query = $this->stokKeluar();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->sum('jumlah');
    }

    // Method untuk mendapatkan laporan stok
    public function getLaporanStok($startDate = null, $endDate = null)
    {
        return [
            'stok_awal' => $this->stok - $this->getTotalStokMasuk($startDate, $endDate) + $this->getTotalStokKeluar($startDate, $endDate),
            'stok_masuk' => $this->getTotalStokMasuk($startDate, $endDate),
            'stok_keluar' => $this->getTotalStokKeluar($startDate, $endDate),
            'stok_akhir' => $this->stok,
        ];
    }
}
