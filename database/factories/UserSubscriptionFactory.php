<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSubscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'price' => $this->faker->numberBetween('10000','100000'),
            'sub_date' =>$this->faker->date(),
            'sub_duration'=>$this->faker->randomNumber(),
            'user_id' => User::factory()->create()->id,
            'subscription_id' => Subscription::factory()->create()->id,
        ];
    }
}
