<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersToJabatan2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('divisi1_id')->nullable()->after('jabatan_id');
            $table->foreign('divisi1_id')->on('divisis')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->uuid('jabatan1_id')->nullable()->after('divisi1_id');
            $table->foreign('jabatan1_id')->on('jabatans')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->uuid('divisi2_id')->nullable()->after('jabatan1_id');
            $table->foreign('divisi2_id')->on('divisis')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->uuid('jabatan2_id')->nullable()->after('divisi2_id');
            $table->foreign('jabatan2_id')->on('jabatans')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'jabatan1_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['divisi1_id']);
                $table->dropColumn('divisi1_id');
                $table->dropForeign(['jabatan1_id']);
                $table->dropColumn('jabatan1_id');
                $table->dropForeign(['divisi2_id']);
                $table->dropColumn('divisi2_id');
                $table->dropForeign(['jabatan2_id']);
                $table->dropColumn('jabatan2_id');
            });
        }
    }
}
