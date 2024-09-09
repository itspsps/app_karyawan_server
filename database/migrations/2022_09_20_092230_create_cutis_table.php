<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->uuid('id')->primary;
            $table->string('nama_cuti');
            $table->string('tanggal');
            $table->string('tanggal_mulai');
            $table->string('tanggal_selesai');
            $table->integer('total_cuti');
            $table->text('keterangan_cuti');
            $table->string('foto_cuti')->nullable();
            $table->string('status_cuti');
            $table->string('approve_atasan');
            $table->string('ttd_atasan')->nullable();
            $table->string('waktu_approve')->nullable();
            $table->string('catatan')->nullable();
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
        Schema::dropIfExists('cutis');
    }
}
