<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(InitialHrdSeeder::class);
        $this->call(DivisionSeeder::class);
        $this->call(PositionSeeder::class);
    }
}
