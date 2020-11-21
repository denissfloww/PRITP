<?php

namespace Database\Seeders;

use App\Models\SubscriptionCapability;
use Illuminate\Database\Seeder;

class SubscriptionCapabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubscriptionCapability::factory(10)->create();
    }
}
