<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('dept_id')->after('is_admin');
            $table->foreign('dept_id')->on('departemens')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->uuid('divisi_id')->after('dept_id');
            $table->foreign('divisi_id')->on('divisis')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->uuid('jabatan_id')->after('divisi_id');
            $table->foreign('jabatan_id')->on('jabatans')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'jabatan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['dept_id']);
                $table->dropColumn('dept_id');
                $table->dropForeign(['divisi_id']);
                $table->dropColumn('divisi_id');
                $table->dropForeign(['jabatan_id']);
                $table->dropColumn('jabatan_id');
            });
        }
    }
};
