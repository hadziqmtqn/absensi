<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'absensi-list',
            'setting-list',
            'data-pasang-baru-list',
            'data-job-list',
            'karyawan-list',
            'profile-list',
            'profile-password',
            'teknisi-cadangan-list',
            'whatsapp-api-create',
            'whatsapp-api-edit',
            'registrasi-create',
            'api-key-list'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
