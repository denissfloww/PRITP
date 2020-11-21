<?php

namespace Database\Factories;

use App\Models\TenderType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderTypeFactory extends Factory
{
    protected $model = TenderType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
        ];
    }
}
