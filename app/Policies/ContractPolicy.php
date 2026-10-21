<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    protected function isFacilityMember(User $user, Contract $contract): bool
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        return $user->facilities()->where('facilities.id', $contract->facility_id)->exists()
            || $user->facility_id == $contract->facility_id;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contract $contract): bool
    {
        return $contract->user_id === $user->id || $this->isFacilityMember($user, $contract);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Contract $contract): bool
    {
        return $this->isFacilityMember($user, $contract);
    }

    public function delete(User $user, Contract $contract): bool
    {
        return $this->isFacilityMember($user, $contract);
    }
}
