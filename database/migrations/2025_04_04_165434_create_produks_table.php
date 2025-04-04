<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->decimal('harga_diskon', 12, 2)->nullable();
            $table->integer('stok');
            $table->decimal('berat', 8, 2)->comment('dalam gram'); // untuk kalkulasi ongkir
            $table->string('satuan')->default('kg'); // kg, pak, butir, dll
            $table->string('foto')->nullable();
            $table->boolean('aktif')->default(true);
            $table->integer('kategori_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
};
