<?php

namespace App\Policies;

use App\Models\Division;
use App\Models\User;

class DivisionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'hrd' || $user->role === 'division_manager';
    }

    public function view(User $user, Division $division): bool
    {
        if ($user->role === 'hrd') {
            return true;
        }

        return $user->role === 'division_manager'
            && $user->division_id !== null
            && $user->division_id === $division->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'hrd';
    }

    public function update(User $user, Division $division): bool
    {
        return $user->role === 'hrd';
    }

    public function delete(User $user, Division $division): bool
    {
        return $user->role === 'hrd';
    }
}
