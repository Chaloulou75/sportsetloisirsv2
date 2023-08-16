<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAdmin(User $user)
    {
        return ($user->email === 'c.jeandey@gmail.com') || ($user->email === 'tonio20@hotmail.fr');
    }
}
