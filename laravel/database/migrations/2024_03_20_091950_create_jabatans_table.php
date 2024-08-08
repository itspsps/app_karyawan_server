<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('divisi_id');
            $table->foreign('divisi_id')->on('divisis')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->uuid('atasan_id')->nullable();
            $table->string('nama_jabatan');
            $table->uuid('level_id');
            $table->foreign('level_id')->on('level_jabatans')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasColumn('jabatans', 'divisi_id')) {
            Schema::table('jabatans', function (Blueprint $table) {
                $table->dropForeign(['divisi_id']);
                $table->dropColumn('divisi_id');
                $table->dropForeign(['level_id']);
                $table->dropColumn('level_id');
            });
        }
    }
}
