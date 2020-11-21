<?php

namespace Database\Seeders;

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
