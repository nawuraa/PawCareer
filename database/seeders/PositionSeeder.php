<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'Veterinary Medicine' => [
                'Veterinarian',
                'Veterinary Resident',
            ],
            'Nursing & Patient Care' => [
                'Veterinary Nurse',
                'Animal Care Technician',
            ],
            'Surgery & ICU' => [
                'Surgery Veterinarian',
                'ICU Nurse',
            ],
            'Administration' => [
                'Clinic Receptionist',
                'HR & Admin Officer',
            ],
            'Grooming & Kennel' => [
                'Grooming Technician',
                'Kennel Attendant',
            ],
            'Operations' => [
                'Operations Coordinator',
                'Inventory & Supply Officer',
            ],
        ];

        foreach ($mapping as $divisionName => $positions) {
            $division = Division::firstOrCreate(['name' => $divisionName]);

            foreach ($positions as $positionName) {
                Position::updateOrCreate(
                    ['division_id' => $division->id, 'name' => $positionName],
                    ['description' => 'Seeded veterinary clinic position for ' . $positionName]
                );
            }
        }
    }
}
