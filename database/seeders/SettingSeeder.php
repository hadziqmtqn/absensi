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
            'awal_absensi' => '08:00:00',
            'akhir_absensi' => '11:00:00',
        ]);
    }
}
