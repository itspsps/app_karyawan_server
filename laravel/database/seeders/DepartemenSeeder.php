<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_data = [
            ['id' => Uuid::uuid4(), 'nama_departemen' => 'HRD & GA', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_departemen' => 'PRODUCTION', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_departemen' => 'SUPPLY CHAIN MANAGEMENT', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_departemen' => 'PROCUREMENT', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_departemen' => 'FINANCE AND ACCOUNTING', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'nama_departemen' => 'SALES AND MARKETING', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('departemens')->insert($jenis_data);
    }
}
