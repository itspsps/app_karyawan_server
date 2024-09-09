<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_data = [
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'HUMAN RESOURCE DEVELOPMENT', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'HRD & GA')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'GENERAL AFFAIRS', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'HRD & GA')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'INFORMATION TECHNOLOGY', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'HRD & GA')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'PRODUCTION', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'PRODUCTION')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'QUALITY CONTROL', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'PRODUCTION')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'MAINTENANCE', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'PRODUCTION')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'PRODUCTION PLANNING INVENTORY CONTROL', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'SUPPLY CHAIN MANAGEMENT')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'DELIVERY', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'SUPPLY CHAIN MANAGEMENT')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'WAREHOUSE', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'SUPPLY CHAIN MANAGEMENT')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'PURCHASING NON MATERIAL', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'PROCUREMENT')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'PURCHASING MATERIAL (SOURCHING)', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'PROCUREMENT')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'ACCOUNTING (GENERAL LEDGER)', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'FINANCE AND ACCOUNTING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'ACCOUNTING (DATABASE)', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'FINANCE AND ACCOUNTING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'FINANCE (ACCOUNT PAYABLE)', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'FINANCE AND ACCOUNTING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'FINANCE (ACCOUNT RECEIVABLE)', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'FINANCE AND ACCOUNTING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'FINANCE (CASH AND BANK - KASIR)', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'FINANCE AND ACCOUNTING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'TAX', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'FINANCE AND ACCOUNTING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'SALES', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'SALES AND MARKETING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_divisi' => 'MARKETING', 'dept_id' => DB::table('departemens')->where('nama_departemen', 'SALES AND MARKETING')->value('id'), 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('divisis')->insert($jenis_data);
    }
}
