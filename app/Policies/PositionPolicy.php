<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;

class PositionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'hrd' || $user->role === 'division_manager';
    }

    public function view(User $user, Position $position): bool
    {
        if ($user->role === 'hrd') {
            return true;
        }

        return $user->role === 'division_manager'
            && $user->division_id !== null
            && $user->division_id === $position->division_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'hrd';
    }

    public function update(User $user, Position $position): bool
    {
        return $user->role === 'hrd';
    }

    public function delete(User $user, Position $position): bool
    {
        return $user->role === 'hrd';
    }
}
