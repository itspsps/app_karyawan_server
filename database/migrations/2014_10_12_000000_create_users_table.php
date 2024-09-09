<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('fullname');
            $table->string('motto');
            $table->string('foto_karyawan')->nullable();
            $table->string('email');
            $table->string('telepon');
            $table->string('username');
            $table->string('password');
            $table->string('tgl_lahir');
            $table->string('gender');
            $table->string('tgl_join');
            $table->string('status_nikah');
            $table->string('cuti_dadakan');
            $table->string('cuti_bersama');
            $table->string('cuti_menikah');
            $table->string('cuti_diluar_tanggungan');
            $table->string('cuti_khusus');
            $table->string('cuti_melahirkan');
            $table->string('izin_telat');
            $table->string('izin_pulang_cepat');
            $table->string('is_admin');
            $table->string('kontrak_kerja');
            $table->string('penempatan_kerja');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('desa');
            $table->string('rt');
            $table->string('rw');
            $table->text('alamat');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
