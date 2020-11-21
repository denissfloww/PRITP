<?php

namespace Database\Seeders;

use App\Models\SubscriptionCapability;
use Database\Factories\SubscriptionCapabilityFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            SubscriptionSeeder::class,
            CapabilitySeeder::class,
            UserSubscriptionSeeder::class,
            SubscriptionCapabilitySeeder::class,
            CurrencySeeder::class,
            CustomerSeeder::class,
            TenderClassifierSeeder::class,
            TenderObjectSeeder::class,
            TenderStageSeeder::class,
            TenderTypeSeeder::class,
            TenderSeeder::class
        ]);
    }
}
