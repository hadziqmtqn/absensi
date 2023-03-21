<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiKey::create([
            'enkripsi' => 'u23423u4i32u4i32',
            'domain' => 'http://localhost:8000',
            'api_key' => '1234567890',
        ]);
    }
}
