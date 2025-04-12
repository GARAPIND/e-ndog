<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promosi extends Model
{
    protected $table = 'banner_promosi';
    protected $fillable = [
        'judul',
        'sub_judul',
        'foto',
    ];
}
