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
            'absensi-create',
            'absensi-edit',
            'absensi-delete',
            'setting-list',
            'data-pasang-baru-list',
            'data-pasang-baru-create',
            'data-pasang-baru-edit',
            'data-pasang-baru-delete',
            'data-job-list',
            'data-job-create',
            'data-job-edit',
            'data-job-delete',
            'karyawan-list',
            'karyawan-create',
            'karyawan-edit',
            'karyawan-delete',
            'profile-list',
            'profile-password',
            'teknisi-cadangan-list',
            'whatsapp-api-list',
            'registrasi-create',
            'online-api-list'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
