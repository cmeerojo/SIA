<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $authUser, User $user)
    {
        return $authUser->role === 'admin' || $authUser->id === $user->id;
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $authUser, User $user)
{
    // Allow admins to edit any user, or users to edit themselves
    return $authUser->role === 'admin' || $authUser->id === $user->id;
}

    public function delete(User $user, User $model)
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}