<?php

namespace App\Policies;

use App\User;
use App\Models\User as Users;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUser, Users $user)
    {
        return $currentUser->id === $user->id;
    }

    public function destroy(User $currentUser, Users $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
