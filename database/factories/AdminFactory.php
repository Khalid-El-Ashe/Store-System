<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name"=> $this->faker->name,
            "email"=> $this->faker->safeEmail,
            'password' => Hash::make('password'), // password
            "username"=> $this->faker->unique()->userName,
            "phone_number"=> $this->faker->unique()->phoneNumber,
            "super_admin"=> $this->faker->boolean, // 50% chance
        ];
    }
}
