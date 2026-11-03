<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'hrd' || $user->role === 'applicant';
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->role === 'hrd') {
            return true;
        }

        return $user->role === 'applicant'
            && $application->applicant_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'applicant';
    }

    public function update(User $user, Application $application): bool
    {
        return $user->role === 'hrd';
    }

    public function delete(User $user, Application $application): bool
    {
        return $user->role === 'hrd';
    }
}
