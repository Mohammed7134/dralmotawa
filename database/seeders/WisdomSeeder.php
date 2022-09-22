<?php

namespace Database\Seeders;

use App\Models\Wisdom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WisdomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wisdom::factory()
            ->count(50)
            ->hasPosts(1)
            ->create();
    }
}
