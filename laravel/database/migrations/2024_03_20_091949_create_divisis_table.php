<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_divisi')->nullable();
            $table->uuid('dept_id');
            $table->foreign('dept_id')->on('departemens')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        if (Schema::hasColumn('divisis', 'dept_id')) {
            Schema::table('divisis', function (Blueprint $table) {
                $table->dropForeign(['dept_id']);
                $table->dropColumn('dept_id');
            });
        }
    }
}
