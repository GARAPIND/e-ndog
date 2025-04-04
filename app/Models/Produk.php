<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'harga',
        'harga_diskon',
        'stok',
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
}
