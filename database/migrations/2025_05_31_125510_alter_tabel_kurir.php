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
            $table->string('latitude', 20)->after('status')->nullable();
            $table->string('longitude', 20)->after('latitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('kurir', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
