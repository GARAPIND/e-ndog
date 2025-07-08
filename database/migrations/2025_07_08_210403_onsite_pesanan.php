<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->boolean('is_onsite')->default(0)->after('is_cod');
            $table->string('nama_pelanggan_onsite')->nullable()->after('is_onsite');
            $table->string('no_telepon_onsite')->nullable()->after('nama_pelanggan_onsite');
            $table->text('alamat_onsite')->nullable()->after('no_telepon_onsite');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'is_onsite',
                'nama_pelanggan_onsite',
                'no_telepon_onsite',
                'alamat_onsite'
            ]);
        });
    }
};
