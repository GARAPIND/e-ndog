<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'aktif'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            $kategori->slug = $kategori->slug ?? Str::slug($kategori->nama);
        });

        static::updating(function ($kategori) {
            $kategori->slug = $kategori->slug ?? Str::slug($kategori->nama);
        });
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
