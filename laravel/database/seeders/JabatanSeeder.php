<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_data = [
            // HRD
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
                // 'atasan_id' => NULL,
                'nama_jabatan' => 'MANAGER HRD',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
                // 'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'MANAGER HRD & GA')->value('id'),
                'nama_jabatan' => 'JUNIOR MANAGER HRD',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
                // 'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'JUNIOR MANAGER HRD & GA')->value('id'),
                'nama_jabatan' => 'SUPERVISOR HRD',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
                // 'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'SUPERVISOR HRD & GA')->value('id'),
                'nama_jabatan' => 'STAFF HRD',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            // GA
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
                // 'atasan_id' => NULL,
                'nama_jabatan' => 'MANAGER GA',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
                // 'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'MANAGER HRD & GA')->value('id'),
                'nama_jabatan' => 'JUNIOR MANAGER GA',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
                // 'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'JUNIOR MANAGER HRD & GA')->value('id'),
                'nama_jabatan' => 'SUPERVISOR GA',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => Uuid::uuid4(),
                'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
                // 'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'SUPERVISOR HRD & GA')->value('id'),
                'nama_jabatan' => 'STAFF GA',
                'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'),
                'created_at' => now(), 'updated_at' => now()
            ],
            // IT
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'INFORMATION TECHNOLOGY')->value('id'), 'nama_jabatan' => 'MANAGER IT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'INFORMATION TECHNOLOGY')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER IT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'INFORMATION TECHNOLOGY')->value('id'), 'nama_jabatan' => 'SUPERVISOR IT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'INFORMATION TECHNOLOGY')->value('id'), 'nama_jabatan' => 'STAFF IT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION')->value('id'), 'nama_jabatan' => 'MANAGER PRODUKSI', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER PRODUKSI', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION')->value('id'), 'nama_jabatan' => 'SUPERVISOR PRODUKSI', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION')->value('id'), 'nama_jabatan' => 'STAFF PRODUKSI', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION')->value('id'), 'nama_jabatan' => 'OPERATOR MESIN DRIYER', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION')->value('id'), 'nama_jabatan' => 'OPERATOR MESIN RMU', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION')->value('id'), 'nama_jabatan' => 'OPERATOR FORKLIPT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'QUALITY CONTROL')->value('id'), 'nama_jabatan' => 'MANAGER QUALITY CONTROL', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'QUALITY CONTROL')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER QUALITY CONTROL', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'QUALITY CONTROL')->value('id'), 'nama_jabatan' => 'SUPERVISOR QUALITY CONTROL', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'QUALITY CONTROL')->value('id'), 'nama_jabatan' => 'STAFF QUALITY CONTROL', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MAINTENANCE')->value('id'), 'nama_jabatan' => 'MANAGER MAINTENANCE', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MAINTENANCE')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER MAINTENANCE', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MAINTENANCE')->value('id'), 'nama_jabatan' => 'SUPERVISOR MAINTENANCE', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MAINTENANCE')->value('id'), 'nama_jabatan' => 'STAFF MAINTENANCE', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION PLANNING INVENTORY CONTROL')->value('id'), 'nama_jabatan' => 'MANAGER PPIC', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION PLANNING INVENTORY CONTROL')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER PPIC', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION PLANNING INVENTORY CONTROL')->value('id'), 'nama_jabatan' => 'SUPERVISOR PPIC', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PRODUCTION PLANNING INVENTORY CONTROL')->value('id'), 'nama_jabatan' => 'STAFF PPIC', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'DELIVERY')->value('id'), 'nama_jabatan' => 'MANAGER DELIVERY', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'DELIVERY')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER DELIVERY', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'DELIVERY')->value('id'), 'nama_jabatan' => 'SPV/KOOR DELIVERY', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'DELIVERY')->value('id'), 'nama_jabatan' => 'STAFF DELIVERY', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'WAREHOUSE')->value('id'), 'nama_jabatan' => 'MANAGER GUDANG', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'WAREHOUSE')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER GUDANG', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'WAREHOUSE')->value('id'), 'nama_jabatan' => 'SPV/KOOR GUDANG', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'WAREHOUSE')->value('id'), 'nama_jabatan' => 'STAFF GUDANG', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING NON MATERIAL')->value('id'), 'nama_jabatan' => 'MANAGER PURCHASING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING NON MATERIAL')->value('id'), 'nama_jabatan' => 'MANAGER PURCHASING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING NON MATERIAL')->value('id'), 'nama_jabatan' => 'SUPERVISOR PURCHASING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING NON MATERIAL')->value('id'), 'nama_jabatan' => 'STAFF PURCHASING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING MATERIAL (SOURCHING)')->value('id'), 'nama_jabatan' => 'MANAGER PROCUREMENT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING MATERIAL (SOURCHING)')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER PROCUREMENT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING MATERIAL (SOURCHING)')->value('id'), 'nama_jabatan' => 'SUPERVISOR SOURCHING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'PURCHASING MATERIAL (SOURCHING)')->value('id'), 'nama_jabatan' => 'STAFF SOURCHING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (GENERAL LEDGER)')->value('id'), 'nama_jabatan' => 'MANAGER PROCUREMENT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (GENERAL LEDGER)')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER PROCUREMENT', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (GENERAL LEDGER)')->value('id'), 'nama_jabatan' => 'SUPERVISOR ACCOUNTING (GENERAL LEDGER)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (GENERAL LEDGER)')->value('id'), 'nama_jabatan' => 'STAFF ACCOUNTING (GENERAL LEDGER)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (DATABASE)')->value('id'), 'nama_jabatan' => 'MANAGER ACCOUNTING (DATABASE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (DATABASE)')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER ACCOUNTING (DATABASE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (DATABASE)')->value('id'), 'nama_jabatan' => 'SUPERVISOR ACCOUNTING (DATABASE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'ACCOUNTING (DATABASE)')->value('id'), 'nama_jabatan' => 'STAFF ACCOUNTING (DATABASE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT PAYABLE)')->value('id'), 'nama_jabatan' => 'MANAGER FINANCE (ACCOUNT PAYABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT PAYABLE)')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER FINANCE (ACCOUNT PAYABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT PAYABLE)')->value('id'), 'nama_jabatan' => 'SUPERVISOR FINANCE (ACCOUNT PAYABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT PAYABLE)')->value('id'), 'nama_jabatan' => 'STAFF FINANCE (ACCOUNT PAYABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT RECEIVABLE)')->value('id'), 'nama_jabatan' => 'MANAGER FINANCE (ACCOUNT RECEIVABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT RECEIVABLE)')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER FINANCE (ACCOUNT RECEIVABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT RECEIVABLE)')->value('id'), 'nama_jabatan' => 'SUPERVISOR FINANCE (ACCOUNT RECEIVABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (ACCOUNT RECEIVABLE)')->value('id'), 'nama_jabatan' => 'STAFF FINANCE (ACCOUNT RECEIVABLE)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (CASH AND BANK - KASIR)')->value('id'), 'nama_jabatan' => 'MANAGER FINANCE (CASH AND BANK - KASIR)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (CASH AND BANK - KASIR)')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER FINANCE (CASH AND BANK - KASIR)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (CASH AND BANK - KASIR)')->value('id'), 'nama_jabatan' => 'SUPERVISOR FINANCE (CASH AND BANK - KASIR)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'FINANCE (CASH AND BANK - KASIR)')->value('id'), 'nama_jabatan' => 'STAFF FINANCE (CASH AND BANK - KASIR)', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'TAX')->value('id'), 'nama_jabatan' => 'MANAGER TAX', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'TAX')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER TAX', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'TAX')->value('id'), 'nama_jabatan' => 'SUPERVISOR TAX', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'TAX')->value('id'), 'nama_jabatan' => 'STAFF TAX', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'SALES')->value('id'), 'nama_jabatan' => 'MANAGER SALES', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'SALES')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER SALES', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'SALES')->value('id'), 'nama_jabatan' => 'SUPERVISOR SALES', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'SALES')->value('id'), 'nama_jabatan' => 'SALES', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MARKETING')->value('id'), 'nama_jabatan' => 'MANAGER MARKETING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MARKETING')->value('id'), 'nama_jabatan' => 'JUNIOR MANAGER MARKETING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MARKETING')->value('id'), 'nama_jabatan' => 'SUPERVISOR MARKETING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => Uuid::uuid4(), 'divisi_id' => DB::table('divisis')->where('nama_divisi', 'MARKETING')->value('id'), 'nama_jabatan' => 'MARKETING', 'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'), 'created_at' => now(), 'updated_at' => now()],
        ];
        // Jabatan::create(
        //     [
        //         'id' => Uuid::uuid4(),
        //         'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
        //         'atasan_id' => NULL,
        //         'nama_jabatan' => 'MANAGER HRD & GA',
        //         'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'),
        //         'created_at' => now(), 'updated_at' => now()
        //     ]
        // );
        // Jabatan::create(
        //     [
        //         'id' => Uuid::uuid4(),
        //         'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
        //         'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'MANAGER HRD & GA')->value('id'),
        //         'nama_jabatan' => 'JUNIOR MANAGER HRD & GA',
        //         'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'),
        //         'created_at' => now(), 'updated_at' => now()
        //     ]
        // );
        // Jabatan::create(
        //     [
        //         'id' => Uuid::uuid4(),
        //         'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
        //         'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'JUNIOR MANAGER HRD & GA')->value('id'),
        //         'nama_jabatan' => 'SUPERVISOR HRD & GA',
        //         'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'),
        //         'created_at' => now(), 'updated_at' => now()
        //     ]
        // );
        // Jabatan::create(
        //     [
        //         'id' => Uuid::uuid4(),
        //         'divisi_id' => DB::table('divisis')->where('nama_divisi', 'HUMAN RESOURCE DEVELOPMENT')->value('id'),
        //         'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'SUPERVISOR HRD & GA')->value('id'),
        //         'nama_jabatan' => 'STAFF HRD',
        //         'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'),
        //         'created_at' => now(), 'updated_at' => now()
        //     ]
        // );
        // GA
        // Jabatan::create(
        //     [
        //         'id' => Uuid::uuid4(),
        //         'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
        //         'atasan_id' => NULL,
        //         'nama_jabatan' => 'MANAGER HRD & GA',
        //         'level_id' => DB::table('level_jabatans')->where('level_jabatan', '1')->value('id'),
        //         'created_at' => now(), 'updated_at' => now()
        //     ]
        // );
        // Jabatan::create([
        //     'id' => Uuid::uuid4(),
        //     'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
        //     'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'MANAGER HRD & GA')->value('id'),
        //     'nama_jabatan' => 'JUNIOR MANAGER HRD & GA',
        //     'level_id' => DB::table('level_jabatans')->where('level_jabatan', '2')->value('id'),
        //     'created_at' => now(), 'updated_at' => now()
        // ]);
        // Jabatan::create([
        //     'id' => Uuid::uuid4(),
        //     'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
        //     'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'JUNIOR MANAGER HRD & GA')->value('id'),
        //     'nama_jabatan' => 'SUPERVISOR HRD & GA',
        //     'level_id' => DB::table('level_jabatans')->where('level_jabatan', '3')->value('id'),
        //     'created_at' => now(), 'updated_at' => now()
        // ]);
        // Jabatan::create([
        //     'id' => Uuid::uuid4(),
        //     'divisi_id' => DB::table('divisis')->where('nama_divisi', 'GENERAL AFFAIRS')->value('id'),
        //     'atasan_id' => DB::table('jabatans')->where('nama_jabatan', 'SUPERVISOR HRD & GA')->value('id'),
        //     'nama_jabatan' => 'STAFF GA',
        //     'level_id' => DB::table('level_jabatans')->where('level_jabatan', '4')->value('id'),
        //     'created_at' => now(), 'updated_at' => now()
        // ]);
        DB::table('jabatans')->insert($jenis_data);
    }
}
