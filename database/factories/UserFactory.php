<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserFactory extends Factory
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
            'role_id' => 2,
            'name' => $this->faker->name(),
            'username' => $this->faker->numberBetween(1000000000,2000000000),
            'short_name' => $this->faker->firstName(),
            'nik' => $this->faker->numberBetween(1234567891234567,3000000000000000),
            'phone' => $this->faker->phoneNumber(),
            'company_name' => $this->faker->company(),
            'photo' => 'theme/template/images/user.png',
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_verifikasi' => rand(0, 1),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
