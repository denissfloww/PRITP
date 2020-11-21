<?php

namespace Database\Factories;

use App\Models\Capability;
use Illuminate\Database\Eloquent\Factories\Factory;

class CapabilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Capability::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=> $this->faker->unique()->text(10),
            'description' => $this->faker->text,
        ];
    }
}
