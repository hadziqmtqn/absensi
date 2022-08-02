<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'application_name' => 'Absensi App',
            'email' => 'absensi@g.com',
            'logo' => 'theme/template/images/logo.svg',
        ]);
    }
}
