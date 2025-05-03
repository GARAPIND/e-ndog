<?php

namespace Database\Seeders;

use App\Models\ProfileToko;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileTokoSeeder extends Seeder
{
    public function run()
    {
        ProfileToko::create([
            'nama_toko' => 'Toko E-NDOG',
            'alamat' => 'Ds. Jagung Kec. Kras, Kab. Kediri',
            'telepon' => '021-12345678',
            'email' => 'endog@gmail.com',
            'deskripsi' => 'Toko E-NDOG adalah toko yang menyediakan berbagai macam telur berkualitas dengan harga terjangkau.',
            'jam_operasional' => 'Senin-Jumat: 09:00 - 17:00, Sabtu: 09:00 - 15:00',
            'facebook' => 'https://facebook.com/tokoonlineku',
            'instagram' => 'https://instagram.com/tokoonlineku',
            'twitter' => 'https://twitter.com/tokoonlineku',
            'whatsapp' => '6281234567890',
            'logo' => 'default_logo.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
