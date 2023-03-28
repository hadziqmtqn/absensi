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

        $permissions = Permission::pluck('id','id')->all();
        
        $role->syncPermissions($permissions);

        $user = User::create([
            'role_id' => 1,
            'name' => 'Admin',
            'idapi' => '12345',
            'email' => 'aa@g.com',
            'password' => bcrypt('12345678'),
            'is_verifikasi' => 1,
        ]);
                
        
        $user->assignRole([$role->id]);
        
        $adminOnline = User::create([
            'role_id' => 1,
            'name' => 'Admin Online',
            'idapi' => '12341234',
            'email' => 'online@gmail.com',
            'password' => bcrypt('12345678'),
            'is_verifikasi' => 1,
        ]);
        
        $adminOnline->assignRole([$role->id]);

        $karyawan = Role::create([
            'name' => 'Karyawan',
        ]);

        $karyawan->givePermissionTo('profile-list');
        $karyawan->givePermissionTo('profile-password');
    }
}
