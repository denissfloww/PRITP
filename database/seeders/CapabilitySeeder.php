<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CapabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Capability::factory(10)->create();
    }
}
