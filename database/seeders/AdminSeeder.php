<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => 1,
            'name' => 'Admin',
            'email' => 'aa@g.com',
            'password' => bcrypt('12345678'),
            'is_verifikasi' => 1,
        ]);
    }
}
