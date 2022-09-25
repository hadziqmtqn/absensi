<?php

namespace Database\Seeders;

use App\Models\WhatsappApi;
use Illuminate\Database\Seeder;

class WhatsappApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WhatsappApi::create([
            'domain' => 'https://jogja.wablas.com',
            'api_keys' => 'Tg5FxW5i3yDcZaZKASXjxfbGwHwARJ8xiynLe0cpddt806aYDLHPEJ6yT6IQqjQ9',
            'no_hp_penerima' => '082337724632',
        ]);
    }
}
