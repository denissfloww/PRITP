<?php

namespace Database\Factories;

use App\Models\TenderClassifier;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderClassifierFactory extends Factory
{
    protected $model = TenderClassifier::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
        ];
    }
}
