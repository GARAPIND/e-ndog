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
        Schema::table('alamat_pelanggan', function (Blueprint $table) {
            $table->string('province_id')->nullable()->after('keterangan');
            $table->string('city_id')->nullable()->after('province_id');
            $table->string('district_id')->nullable()->after('city_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alamat_pelanggan', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'city_id', 'district_id']);
        });
    }
};
