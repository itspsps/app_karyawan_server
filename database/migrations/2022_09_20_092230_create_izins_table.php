<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIzinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('izins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            $table->string('departements_id');
            $table->string('jabatan_id');
            $table->string('divisi_id');
            $table->string('telp');
            $table->string('email');
            $table->string('fullname');
            $table->string('izin');
            $table->string('tanggal');
            $table->string('jam');
            $table->text('keterangan_izin');
            $table->string('foto_izin')->nullable();
            $table->string('approve_atasan');
            $table->string('id_approve_atasan');
            $table->integer('status_izin');
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
        Schema::dropIfExists('izins');
    }
}
