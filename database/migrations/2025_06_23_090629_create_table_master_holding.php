<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMasterHolding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holdings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('holding_code');
            $table->string('holding_name');
            $table->string('holding_category');
            $table->string('holding_image');
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
        Schema::dropIfExists('holdings');
    }
}
