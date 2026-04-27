<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('bank_employee')) {
            return true;
        }
        return false;
    }

    public function view(User $user, Loan $loan): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('bank_employee')) {
            return (int) $user->bank_id === (int) $loan->bank_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('bank_employee')) {
            return true;
        }
        return false;
    }

    public function update(User $user, Loan $loan): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('bank_employee')) {
            return (int) $user->bank_id === (int) $loan->bank_id;
        }
        return false;
    }

    public function delete(User $user, Loan $loan): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('bank_employee')) {
            return (int) $user->bank_id === (int) $loan->bank_id;
        }
        return false;
    }
}
