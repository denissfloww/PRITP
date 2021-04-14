<?php

namespace Database\Factories;

use App\Models\TenderObject;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderObjectFactory extends Factory
{
    protected $model = TenderObject::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'okvad2_classifier' => $this->faker->numerify('###.###.###')

        ];
    }
}
