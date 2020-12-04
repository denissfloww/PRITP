<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Tender;
use App\Models\TenderClassifier;
use App\Models\TenderObject;
use App\Models\TenderStage;
use App\Models\TenderType;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenderSeeder extends Seeder
{
    public function run()
    {
        Tender::factory()
            ->count(10)
            ->for(TenderType::factory(), 'type')
            ->for(TenderStage::factory(), 'stage')
            ->for(TenderClassifier::factory(), 'classifier')
            ->for(Customer::factory())
            ->for(Currency::factory())
            ->hasAttached(
                TenderObject::factory()->count(3),
                ['quantity' => 13],
                'objects'
            )
            ->hasAttached(
                User::factory()->count(3),
                [],
                'favoriteUsers'
            )
            ->create();
    }
}
