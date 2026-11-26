<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialManagerSeeder extends Seeder
{
    public function run(): void
    {
        $division = Division::where('name', 'Nursing & Patient Care')->first()
            ?? Division::orderBy('id')->first();

        if (! $division) {
            return;
        }

        $email = env('INITIAL_MANAGER_EMAIL', 'manager@pawcareer.local');
        $name = env('INITIAL_MANAGER_NAME', 'Initial MANAGER');
        $password = env('INITIAL_MANAGER_PASSWORD', 'ChangeThisPassword123!');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'division_manager',
                'division_id' => $division->id,
            ]
        );
    }
}