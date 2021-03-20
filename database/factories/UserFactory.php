<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'middle_name' => $this->faker->firstName,
            'inn' => $this->faker->unique()->numerify('############')
        ];
    }
}
