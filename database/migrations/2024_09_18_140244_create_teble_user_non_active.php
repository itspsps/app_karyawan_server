<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTebleUserNonActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usernonactive', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            $table->string('tanggal_non_active');
            $table->string('alasan');
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
        Schema::dropIfExists('usernonactive');
    }
}
