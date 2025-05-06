<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_transaksi');
            $table->string('tanggal_transaksi');
            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('alamat_id');
            $table->string('status_pembayaran')->nullable();
            $table->string('status_pengiriman')->nullable();
            $table->tinyInteger('is_cod')->default(0);
            $table->unsignedBigInteger('kurir_id')->nullable();
            $table->string('ekspedisi')->nullable();
            $table->bigInteger('sub_total');
            $table->bigInteger('ongkir');
            $table->text('catatan_pelanggan')->nullable();
            $table->text('catatan_penjual')->nullable();
            $table->timestamps();

            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('alamat_id')->references('id')->on('alamat_pelanggan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kurir_id')->references('id')->on('kurir')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
