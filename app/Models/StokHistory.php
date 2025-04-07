<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokHistory extends Model
{
    protected $table = 'stok_history';

    protected $fillable = [
        'produk_id',
        'jumlah',
        'stok_sebelum',
        'stok_setelah',
        'tipe',
        'keterangan',
        'referensi_id',
        'referensi_tipe',
        'user_id'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
