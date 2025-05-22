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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->tinyInteger('cancel')->nullable()->after('catatan_penjual')->comment('0 = proses, 1 = batal, 2 = tolak');
            $table->text('catatan_cancel')->nullable()->after('cancel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('cancel');
            $table->dropColumn('catatan_cancel');
        });
    }
};
