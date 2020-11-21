<?php

namespace Database\Seeders;

use App\Models\TenderClassifier;
use Illuminate\Database\Seeder;

class TenderClassifierSeeder extends Seeder
{
    public function run()
    {
        TenderClassifier::factory()
            ->count(10)
            ->for(TenderClassifier::factory(), 'parent')
            ->create();
    }
}
