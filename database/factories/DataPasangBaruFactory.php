<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Faker\Factory as Faker;

class DataPasangBaruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create('id_ID');
        return [
            'kode' => 'SC-'.$this->faker->numberBetween(1000000000,2000000000),
            'inet' => $this->faker->numberBetween(3000000000,4000000000),
            'nama_pelanggan' => $this->faker->name(),
            'no_hp' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'acuan_lokasi' => $this->faker->realText(),
        ];
    }
}
