<?php

namespace Database\Seeders;

use App\Models\TenderStage;
use Illuminate\Database\Seeder;

class TenderStageSeeder extends Seeder
{
    public function run()
    {
        TenderStage::factory()
            ->count(10)
            ->create();
    }
}
