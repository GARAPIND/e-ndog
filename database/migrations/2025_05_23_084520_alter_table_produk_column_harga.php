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
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('harga_grosir', 12, 2)->after('harga');
            $table->decimal('harga_pengampu', 12, 2)->after('harga_grosir');
            $table->dropColumn('harga_diskon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('harga_diskon', 12, 2)->nullable();
            $table->dropColumn('harga_grosir');
            $table->dropColumn('harga_pengampu');
        });
    }
};
