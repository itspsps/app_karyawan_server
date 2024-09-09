<?php

namespace Database\Seeders;

use App\Models\Cuti;
use App\Models\Jabatan;
use App\Models\KategoriCuti;
use App\Models\LevelJabatan;
use App\Models\Lokasi;
use App\Models\ResetCuti;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Cuti::create([
        //     'id' => Uuid::uuid4(),
        //     'user_id' => DB::table('users')->where('name', 'User2')->value('id'),
        //     'nama_cuti' => "Cuti",
        //     'tanggal' => date("Y-m-d"),
        //     'alasan_cuti' => "Cuti",
        //     'foto_cuti' => NULL,
        //     'status_cuti' => "0",
        //     'catatan' => "-",
        // ]);

        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Cuti Dadakan",
            'jumlah_cuti' => "1",
            'status' => "1",
        ]);
        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Cuti Melahirkan",
            'jumlah_cuti' => "30",
            'status' => "1",
        ]);
        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Cuti Istri Melahirkan",
            'jumlah_cuti' => "2",
            'status' => "1",
        ]);
        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Cuti Istri Keguguran",
            'jumlah_cuti' => "2",
            'status' => "1",
        ]);
        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Cuti Menikah",
            'jumlah_cuti' => "3",
            'status' => "1",
        ]);
        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Cuti Menikahkan Anak",
            'jumlah_cuti' => "2",
            'status' => "1",
        ]);
        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Cuti Mengkhitankan Anak",
            'jumlah_cuti' => "2",
            'status' => "1",
        ]);
        KategoriCuti::create([
            'id' => Uuid::uuid4(),
            'nama_cuti' => "Sakit",
            'jumlah_cuti' => "1",
            'status' => "1",
        ]);

        Shift::create([
            'id' => Uuid::uuid4(),
            'nama_shift' => "Libur",
            'jam_masuk' => "00:00",
            'jam_keluar' => "00:00",
        ]);

        Shift::create([
            'id' => Uuid::uuid4(),
            'nama_shift' => "Office",
            'jam_masuk' => "08:00",
            'jam_keluar' => "17:00",
        ]);

        Shift::create([
            'id' => Uuid::uuid4(),
            'nama_shift' => "Siang",
            'jam_masuk' => "13:00",
            'jam_keluar' => "21:00",
        ]);

        Shift::create([
            'id' => Uuid::uuid4(),
            'nama_shift' => "Malam",
            'jam_masuk' => "21:00",
            'jam_keluar' => "07:00",
        ]);

        Lokasi::create([
            'lat_kantor' => '-7.810916',
            'long_kantor' => '112.080001',
            'radius' => '200',
        ]);

        ResetCuti::create([
            'cuti_dadakan' => 10,
            'cuti_bersama' => 10,
            'cuti_menikah' => 10,
            'cuti_diluar_tanggungan' => 10,
            'cuti_khusus' => 10,
            'cuti_melahirkan' => 10,
            'izin_telat' => 10,
            'izin_pulang_cepat' => 10
        ]);
    }
}
