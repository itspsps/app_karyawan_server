<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMappingShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapping_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('jam_absen')->nullable();
            $table->string('telat')->nullable();
            $table->string('lat_absen')->nullable();
            $table->string('long_absen')->nullable();
            $table->string('jarak_masuk')->nullable();
            $table->string('foto_jam_absen')->nullable();
            $table->string('jam_pulang')->nullable();
            $table->string('pulang_cepat')->nullable();
            $table->string('lembur')->nullable();
            $table->string('foto_jam_pulang')->nullable();
            $table->string('lat_pulang')->nullable();
            $table->string('long_pulang')->nullable();
            $table->string('jarak_pulang')->nullable();
            $table->string('status_absen')->nullable();
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
        Schema::dropIfExists('mapping_shifts');
    }
}
