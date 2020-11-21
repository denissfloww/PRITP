<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'inn' => $this->faker->unique()->numerify('#########'),
            'kpp' => $this->faker->unique()->numerify('#########'),
            'ogrn' => $this->faker->unique()->numerify('########'),
            'region' => $this->faker->state,
            'region_id' => $this->faker->unique()->randomNumber(),
            'place' => $this->faker->state,
            'place_id' => $this->faker->unique()->randomNumber(),
            'cp_name' => $this->faker->name,
            'cp_email' => $this->faker->unique()->email,
            'cp_phone' => $this->faker->unique()->phoneNumber,
        ];
    }
}
