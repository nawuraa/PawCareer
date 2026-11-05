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
        if ($user->role === 'hrd' && $interview->interviewer_id === $user->id) {
            return true;
        }

        return $user->role === 'applicant'
            && $interview->application?->applicant_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'hrd';
    }

    public function update(User $user, Interview $interview): bool
    {
        return $this->evaluate($user, $interview);
    }

    public function evaluate(User $user, Interview $interview): bool
    {
        return $user->role === 'hrd'
            && $interview->interviewer_id === $user->id
            && $interview->status === 'scheduled';
    }

    public function delete(User $user, Interview $interview): bool
    {
        return $user->role === 'hrd';
    }
}
