<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Veterinary Medicine',
            'Nursing & Patient Care',
            'Surgery & ICU',
            'Administration',
            'Grooming & Kennel',
            'Operations',
        ];

        foreach ($divisions as $divisionName) {
            Division::updateOrCreate(
                ['name' => $divisionName],
                ['description' => 'Seeded veterinary clinic division for ' . $divisionName]
            );
        }
    }
}
