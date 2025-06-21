<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel untuk stok masuk
        Schema::create('stok_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_setelah');
            $table->string('tipe');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->string('referensi_tipe')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['produk_id', 'created_at']);
            $table->index(['referensi_id', 'referensi_tipe']);
        });

        // Tabel untuk stok keluar
        Schema::create('stok_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_setelah');
            $table->string('tipe');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->string('referensi_tipe')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['produk_id', 'created_at']);
            $table->index(['referensi_id', 'referensi_tipe']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_keluar');
        Schema::dropIfExists('stok_masuk');
    }
};
