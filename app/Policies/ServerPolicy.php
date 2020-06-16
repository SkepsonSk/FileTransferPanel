<?php

namespace App\Policies;

use App\Server;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ServerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the server files.
     *
     * @param User $user
     * @param Server $server
     * @return boolean
     */
    public function view(User $user, Server $server)
    {
        $id = $server->id;
        $count = $user->permissions()->where('server_id', $id)->count();

        if ($count > 0) return Response::allow();
        return Response::deny('Brak dostępu do serwera');
    }

    /**
     * Determine whether the user can move the files on the server.
     *
     * @param User $user
     * @param Server $server
     * @return boolean
     */
    public function move(User $user, Server $server)
    {
        $id = $server->id;
        $perm = $user->permissions()->where('server_id', $id)->first();

        if ($perm != null) {
            $level = $perm->level;
            if ($level >= 125) return Response::allow();
            return Response::deny('Brak uprawnień.');
        }
    }

    /**
     * Determine whether the user can create servers.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        $power = $user->power()->first()->power;

        if ($power >= 500)
            return Response::allow();
        return Response::deny('Brak uprawnień.');
    }

    /**
     * Determine whether the user can upload files to the server.
     *
     * @param User $user
     * @param Server $server
     * @return mixed
     */
    public function upload(User $user, Server $server)
    {
        $id = $server->id;
        $perm = $user->permissions()->where('server_id', $id)->first();

        if ($perm != null) {
            $level = $perm->level;
            if ($level >= 100) return Response::allow();
            return Response::deny('Brak uprawnień do dodawania plików.');
        }
    }

    /**
     * Determine whether the user can create files and directories.
     *
     * @param User $user
     * @param Server $server
     * @return mixed
     */
    public function createFiles(User $user, Server $server)
    {
        $id = $server->id;
        $perm = $user->permissions()->where('server_id', $id)->first();

        if ($perm != null) {

            $level = $perm->level;
            if ($level >= 150) return Response::allow();
            return Response::deny('Brak uprawnień do dodawania plików.');

        }
    }

    /**
     * Determine whether the user can modify server files (writing).
     *
     * @param User $user
     * @param Server $server
     * @return mixed
     */
    public function modifyFiles(User $user, Server $server)
    {
        $id = $server->id;
        $perm = $user->permissions()->where('server_id', $id)->first();

        if ($perm != null) {

            $level = $perm->level;
            if ($level >= 200) return Response::allow();
            return Response::deny('Brak uprawnień do dodawania plików.');

        }
    }

    /**
     * Determine whether the user can download files from the server.
     *
     * @param User $user
     * @param Server $server
     * @return mixed
     */
    public function download(User $user, Server $server)
    {
        $id = $server->id;
        $perm = $user->permissions()->where('server_id', $id)->first();

        if ($perm != null) {

            $level = $perm->level;
            if ($level >= 250) return Response::allow();
            return Response::deny('Brak uprawnień.');

        }
    }

    /**
     * Determine whether the user can delete files from the server.
     *
     * @param User $user
     * @param Server $server
     * @return mixed
     */
    public function delete(User $user, Server $server)
    {
        $id = $server->id;
        $perm = $user->permissions()->where('server_id', $id)->first();

        if ($perm != null) {

            $level = $perm->level;
            if ($level >= 300) return Response::allow();
            return Response::deny('Brak uprawnień do usuwania plików.');

        }
    }

    /**
     * Determine whether the user
     * can delete a server.
     *
     * @param User $user
     * @param Server $server
     * @return mixed
     */
    public function destroy(User $user)
    {
        $power = $user->power()->first()->power;

        if ($power >= 750)
            return Response::allow();
        return Response::deny('Brak uprawnień.');
    }

}
