<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    public function view(User $user)
    {
        // Hanya admin yang dapat mengakses dashboard
        return $user->role->name === 'admin';
    }
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
}
