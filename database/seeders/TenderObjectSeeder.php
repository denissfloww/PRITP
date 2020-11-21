<?php

namespace Database\Seeders;

use App\Models\TenderObject;
use Illuminate\Database\Seeder;

class TenderObjectSeeder extends Seeder
{
    public function run()
    {
        TenderObject::factory()
            ->count(10)
            ->create();
    }
}
