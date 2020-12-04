<?php

namespace Database\Seeders;

use App\Models\TenderClassifier;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenderClassifierSeeder extends Seeder
{
    public function run()
    {
        TenderClassifier::factory()
            ->count(10)
            ->for(TenderClassifier::factory(), 'parent')
            ->hasAttached(
                User::factory()->count(3),
                [],
                'mailingUsers'
            )
            ->create();
    }
}
