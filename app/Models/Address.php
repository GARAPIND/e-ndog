<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'alamat_pelanggan';
    protected $fillable = [
        'pelanggan_id',
        'alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'kode_pos',
        'keterangan',
        'is_primary',
        'latitude',
        'longitude',
        'province_id',
        'city_id',
        'district_id',

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'pelanggan_id');
    }
}
