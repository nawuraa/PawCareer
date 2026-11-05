<?php

namespace App\Policies;

use App\Models\Requisition;
use App\Models\User;

class RequisitionPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['hrd', 'division_manager'], true);
    }

    public function view(User $user, Requisition $requisition): bool
    {
        if ($user->role === 'hrd') {
            return true;
        }

        return $user->role === 'division_manager'
            && $user->division_id !== null
            && $requisition->position !== null
            && $requisition->position->division_id == $user->division_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'division_manager' && $user->division_id !== null;
    }

    public function update(User $user, Requisition $requisition): bool
    {
        if ($user->role === 'division_manager') {
            return $user->division_id !== null
                && $requisition->status === 'submitted'
                && $requisition->position !== null
                && $requisition->position->division_id == $user->division_id
                && $requisition->requested_by == $user->id;
        }

        return false;
    }

    public function delete(User $user, Requisition $requisition): bool
    {
        return $this->update($user, $requisition);
    }
}
