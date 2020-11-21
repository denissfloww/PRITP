<?php

namespace Database\Factories;

use App\Models\Capability;
use App\Models\Subscription;
use App\Models\SubscriptionCapability;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionCapabilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscriptionCapability::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subscription_id' => Subscription::factory()->create()->id,
            'capability_id' => Capability::factory()->create()->id,
        ];
    }
}
