<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
        Schema::table('transaksi', function (Blueprint $table) {
            $table->text('catatan_kurir')->nullable()->after('catatan_pelanggan');
            $table->string('foto')->nullable()->after('catatan_kurir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('foto');
            $table->dropColumn('catatan_kurir');
        });
    }
};
