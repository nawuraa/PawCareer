<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;

class JobPostingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, JobPosting $jobPosting): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'hrd';
    }

    public function update(User $user, JobPosting $jobPosting): bool
    {
        return $user->role === 'hrd';
    }

    public function delete(User $user, JobPosting $jobPosting): bool
    {
        return $user->role === 'hrd';
    }
}
