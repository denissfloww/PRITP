<?php

namespace Database\Seeders;

use App\Models\TenderType;
use Illuminate\Database\Seeder;

class TenderTypeSeeder extends Seeder
{
    public function run()
    {
        TenderType::factory()
            ->count(10)
            ->create();
    }
}
