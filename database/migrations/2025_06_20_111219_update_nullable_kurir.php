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
        Schema::table('kurir', function (Blueprint $table) {
            $table->string('alamat')->nullable()->change();
            $table->string('plat_nomor')->nullable()->change();
            $table->string('jenis_kendaraan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kurir', function (Blueprint $table) {
            $table->string('alamat')->nullable(false)->change();
            $table->string('plat_nomor')->nullable(false)->change();
            $table->string('jenis_kendaraan')->nullable(false)->change();
        });
    }
};
