<?php

namespace Database\Factories;

use App\Models\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderFactory extends Factory
{
    protected $model = Tender::class;

    public function definition()
    {
        return [
            'number' => $this->faker->unique()->buildingNumber,
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'source_url' => $this->faker->url,
            'start_request_date' => $this->faker->dateTime(),
            'end_request_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'result_date' => $this->faker->dateTimeBetween('+1 year', '+2 year'),
            'nmc_price' => $this->faker->randomFloat(2, 0, 10000),
            'ensure_request_price' => $this->faker->randomFloat(2, 0, 10000),
            'ensure_contract_price' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
