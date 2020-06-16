<?php

namespace App\Policies;

use App\Power;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PowersPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can modify other users powers.
     *
     * @param User $user
     * @param Power $power
     * @return mixed
     */
    public function update(User $user, Power $power)
    {
        $powerUser = $power->user()->first();

        if ($powerUser->id == $user->id)
            return Response::deny('Brak uprawnień do modyfikacji własnej mocy.');

        $powerValue = $user->power()->first()->power;

        if ($powerValue >= 1750) return Response::allow();
        Response::deny('Brak uprawnień do modyfikacji mocy użytkowników.');
    }

}
