<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResetCutisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reset_cutis', function (Blueprint $table) {
            $table->id();
            $table->string('cuti_dadakan');
            $table->string('cuti_bersama');
            $table->string('cuti_menikah');
            $table->string('cuti_diluar_tanggungan');
            $table->string('cuti_khusus');
            $table->string('cuti_melahirkan');
            $table->string('izin_telat');
            $table->string('izin_pulang_cepat');
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
        Schema::dropIfExists('reset_cutis');
    }
}
