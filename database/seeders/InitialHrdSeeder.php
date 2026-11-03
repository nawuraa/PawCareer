<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialHrdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('INITIAL_HRD_EMAIL');
        $password = env('INITIAL_HRD_PASSWORD');

        if (empty($email) || empty($password)) {
            $this->command->error('INITIAL_HRD_EMAIL and INITIAL_HRD_PASSWORD must be set in the environment before seeding the initial HRD account.');

            return;
        }

        DB::transaction(function () use ($email, $password) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Initial HRD',
                    'password' => Hash::make($password),
                    'role' => 'hrd',
                    'division_id' => null,
                ]
            );

            $this->command->info('Initial HRD ensured: ' . $user->email);
        });
    }
}
