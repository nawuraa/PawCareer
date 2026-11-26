<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('INITIAL_MANAGER_EMAIL');
        $password = env('INITIAL_MANAGER_PASSWORD');

        if (empty($email) || empty($password)) {
            $this->command->error('INITIAL_MANAGER_EMAIL and INITIAL_MANAGER_PASSWORD must be set in the environment before seeding the initial MANAGER account.');

            return;
        }

        DB::transaction(function () use ($email, $password) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Initial MANAGER',
                    'password' => Hash::make($password),
                    'role' => 'division_manager',
                    'division_id' => 41,
                ]
            );

            $this->command->info('Initial MANAGER ensured: ' . $user->email);
        });
    }
}
