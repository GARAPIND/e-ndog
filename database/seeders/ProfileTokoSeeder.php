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
            'deskripsi' => 'Toko E-NDOG adalah toko yang menyediakan berbagai macam telur berkualitas tinggi, mulai dari telur ayam ras, telur ayam kampung, hingga telur bebek, dengan harga yang terjangkau untuk semua kalangan. Kami berkomitmen untuk memberikan produk yang segar, bersih, dan layak konsumsi setiap harinya. Dengan pelayanan yang ramah dan profesional, Toko E-NDOG menjadi pilihan terpercaya bagi masyarakat sekitar yang membutuhkan pasokan telur untuk kebutuhan rumah tangga, usaha kuliner, maupun keperluan acara',
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
