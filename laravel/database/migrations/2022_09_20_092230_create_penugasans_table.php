<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenugasansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->uuid('id')->primary;
            $table->string('id_user');
            $table->string('id_user_atasan');
            $table->string('id_user_atasan2');
            $table->string('id_jabatan');
            $table->string('id_departemen');
            $table->string('id_divisi');
            $table->string('id_diajukan_oleh');
            $table->string('ttd_id_diajukan_oleh');
            $table->string('waktu_ttd_id_diajukan_oleh');
            $table->string('id_diminta_oleh');
            $table->string('ttd_id_diminta_oleh');
            $table->string('waktu_ttd_id_diminta_oleh');
            $table->string('id_disahkan_oleh');
            $table->string('ttd_id_disahkan_oleh');
            $table->string('waktu_ttd_id_disahkan_oleh');
            $table->string('proses_hrd');
            $table->string('ttd_proses_hrd');
            $table->string('waktu_ttd_proses_hrd');
            $table->string('proses_finance');
            $table->string('ttd_proses_finance');
            $table->string('waktu_ttd_proses_finance');
            $table->string('penugasan');
            $table->string('tanggal_kunjungan');
            $table->string('kegiatan_penugasan');
            $table->string('pic_dikunjungi');
            $table->string('alamat_dikunjungi');
            $table->string('transportasi');
            $table->string('kelas');
            $table->string('budget_hotel');
            $table->string('makan');
            $table->string('status');
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
