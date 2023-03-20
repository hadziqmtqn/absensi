<?php

namespace Database\Seeders;

use App\Models\OnlineApi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnlineApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OnlineApi::create([
            'website' => 'http://localhost:8001'
        ]);
    }
}
