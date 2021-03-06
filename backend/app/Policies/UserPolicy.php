<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function as(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }
}
