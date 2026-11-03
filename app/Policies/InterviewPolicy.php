<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'hrd';
    }

    public function view(User $user, Interview $interview): bool
    {
        return $user->role === 'hrd';
    }

    public function create(User $user): bool
    {
        return $user->role === 'hrd';
    }

    public function update(User $user, Interview $interview): bool
    {
        return $user->role === 'hrd';
    }

    public function delete(User $user, Interview $interview): bool
    {
        return $user->role === 'hrd';
    }
}
