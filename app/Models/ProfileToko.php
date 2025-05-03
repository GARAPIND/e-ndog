<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileToko extends Model
{
    use HasFactory;
    protected $table = 'profile_toko';
    protected $fillable = [
        'nama_toko',
        'logo',
        'alamat',
        'telepon',
        'email',
        'deskripsi',
        'jam_operasional',
        'facebook',
        'instagram',
        'twitter',
        'whatsapp'
    ];
}
