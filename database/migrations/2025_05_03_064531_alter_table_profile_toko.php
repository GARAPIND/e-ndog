<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profile_toko', function (Blueprint $table) {
            $table->string('latitude')->nullable()->after('whatsapp');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_toko', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
