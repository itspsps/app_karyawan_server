<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create-karyawan']);
        Permission::create(['name' => 'edit-karyawan']);
        Permission::create(['name' => 'delete-karyawan']);
        Permission::create(['name' => 'mappingshift-karyawan']);


        $adminRole = Role::create(['name' => 'Admin']);
        $superadmin = Role::create(['name' => 'Superadmin']);

        $adminRole->givePermissionTo([
            'mappingshift-karyawan',

        ]);

        $superadmin->givePermissionTo([
            'create-karyawan',
            'edit-karyawan',
            'delete-karyawan',
        ]);
    }
}
