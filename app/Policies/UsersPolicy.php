<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create other users.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        $power = $user->power()->first()->power;

        if ($power >= 1000)
            return Response::allow();
        return Response::deny('Brak uprawnień.');
    }

    /**
     * Determine whether the user can update users.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        $power = $user->power()->first()->power;

        if ($power >= 1250)
            return Response::allow();
        return Response::deny('Brak uprawnień.');
    }

    /**
     * Determine whether the user can modify
     * other users servers permissions.
     *
     * @param User $user
     * @return mixed
     */
    public function modifyPermissions(User $user)
    {
        $power = $user->power()->first()->power;

        if ($power >= 1500)
            return Response::allow();
        return Response::deny('Brak uprawnień.');
    }

    /**
     * Determine whether the user can
     * modify other users powers.
     *
     * @param User $user
     * @return mixed
     */
    public function modifyPowers(User $user)
    {
        $power = $user->power()->first()->power;

        if ($power >= 1750)
            return Response::allow();
        return Response::deny('Brak uprawnień.');
    }

    /**
     * Determine whether the user can delete other users.
     *
     * @param User $user
     * @return mixed
     */
    public function destroy(User $user)
    {
        $power = $user->power()->first()->power;

        if ($power >= 2000)
            return Response::allow();
        return Response::deny('Brak uprawnień.');
    }
}
