<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Seed principal
    public function run(): void
    {
        $this->call(CocinaLabSeeder::class);
    }
}
