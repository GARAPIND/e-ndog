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
        'harga_diskon',
        'stok',
        'stok_minimum',
        'notifikasi_stok',
        'berat',
        'satuan',
        'foto',
        'aktif',
        'kategori_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
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
    protected $casts = [
        'aktif' => 'boolean',
        'notifikasi_stok' => 'boolean',
    ];

    public function stokHistories()
    {
        return $this->hasMany(StokHistory::class);
    }

    // Metode untuk menambah stok
    public function tambahStok($jumlah, $keterangan = null, $referensi_id = null, $referensi_tipe = null)
    {
        return $this->updateStok($jumlah, 'masuk', $keterangan, $referensi_id, $referensi_tipe);
    }

    // Metode untuk mengurangi stok
    public function kurangiStok($jumlah, $keterangan = null, $referensi_id = null, $referensi_tipe = null)
    {
        return $this->updateStok(-$jumlah, 'keluar', $keterangan, $referensi_id, $referensi_tipe);
    }

    // Metode untuk menyesuaikan stok
    public function sesuaikanStok($jumlah_baru, $keterangan = null)
    {
        $perubahan = $jumlah_baru - $this->stok;
        $tipe = $perubahan >= 0 ? 'adjustment_tambah' : 'adjustment_kurang';

        return $this->updateStok($perubahan, $tipe, $keterangan);
    }

    // Metode umum untuk update stok
    private function updateStok($jumlah, $tipe, $keterangan = null, $referensi_id = null, $referensi_tipe = null)
    {
        $stok_sebelum = $this->stok;
        $stok_setelah = $stok_sebelum + $jumlah;

        if ($stok_setelah < 0) {
            return false;
        }

        $this->stok = $stok_setelah;
        $saved = $this->save();

        if ($saved) {
            // Buat catatan histori
            StokHistory::create([
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
}
