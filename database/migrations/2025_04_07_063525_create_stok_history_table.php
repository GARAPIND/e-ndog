<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id');
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_setelah');
            $table->string('tipe', 20); 
            $table->string('keterangan')->nullable();
            $table->string('referensi_id')->nullable();
            $table->string('referensi_tipe')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->integer('stok_minimum')->default(5)->after('stok');
            $table->boolean('notifikasi_stok')->default(true)->after('stok_minimum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('notifikasi_stok');
            $table->dropColumn('stok_minimum');
        });

        Schema::dropIfExists('stok_history');
    }
};
