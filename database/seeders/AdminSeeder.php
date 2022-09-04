<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
            'name' => 'Admin',
        ]);

        Role::create([
            'name' => 'Karyawan',
        ]);
       
        $user = User::create([
            'role_id' => 1,
            'name' => 'Admin',
            'email' => 'aa@g.com',
            'password' => bcrypt('12345678'),
            'is_verifikasi' => 1,
        ]);

        $permissions = Permission::pluck('id','id')->all();
     
        $role->syncPermissions($permissions);
       
        $user->assignRole([$role->id]);
    }
}
