<?php

namespace App\Models;

use Egulias\EmailValidator\Result\Reason\DetailedReason;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'tanggal_transaksi',
        'pelanggan_id',
        'alamat_id',
        'status_pembayaran',
        'status_pengiriman',
        'is_cod',
        'kurir_id',
        'ekspedisi',
        'sub_total',
        'ongkir',
        'jarak',
        'catatan_pelanggan',
        'catatan_penjual',
        'foto',
        'snap_token'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function alamat()
    {
        return $this->belongsTo(Address::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Customer::class);
    }
    public function kurir()
    {
        return $this->belongsTo(Kurir::class);
    }
    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
