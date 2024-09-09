<?php

namespace Database\Seeders;

use App\Models\LevelJabatan;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class levelJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name_data = [
            '1',
            '2',
            '3',
            '4',
            '5',
        ];

        foreach ($name_data as $name) {
            LevelJabatan::create([
                'id' =>  Uuid::uuid4(),
                'level_jabatan' => $name,
            ]);
        }
    }
}
