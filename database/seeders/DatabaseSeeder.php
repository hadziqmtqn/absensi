<?php

namespace Database\Seeders;

use App\Models\DataPasangBaru;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTableSeeder::class,
            AdminSeeder::class,
            SettingSeeder::class,
            WhatsappApiSeeder::class,
        ]);
        DataPasangBaru::factory(5)->create();

        $users = User::factory(5)->create();
        foreach($users as $user){
            $user->assignRole('2');
        }
    }
}
