<?php

namespace Database\Factories;

use App\Models\TenderStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderStageFactory extends Factory
{
    protected $model = TenderStage::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
        ];
    }
}
