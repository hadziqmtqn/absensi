<?php

namespace Database\Seeders;

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
        ]);
        \App\Models\User::factory(20)->create();
        \App\Models\DataPasangBaru::factory(20)->create();
    }
}
