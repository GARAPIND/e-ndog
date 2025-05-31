<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    use HasFactory;

    protected $table = 'kurir';
    protected $fillable = [
        'user_id',
        'telp',
        'alamat',
        'plat_nomor',
        'jenis_kendaraan',
        'photo',
        'status',
        'latitude',
        'longitude',
    ];

    /**
     * Get the user that owns the courier profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function deliveries()
    // {
    //     return $this->hasMany(Delivery::class, 'kurir_id');
    // }
}
