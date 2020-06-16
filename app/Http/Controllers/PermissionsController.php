<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Server;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionsController extends Controller
{

    public function permissions(Request $request) {
        $id = $request->input('id');
        $user = User::find($id);
        $permissions = $user->permissions()->get();

        $perms = [];
        foreach ($permissions as $perm) {
            $server = $perm->server()->first();
            array_push($perms, ['server' => $server->name, 'level' => $perm->level]);
        }

        return response()->json($perms);
    }

    public function give(Request $request) {

        $user = Auth::user();

        if ($user->can('modify-permissions', User::class)) {
            $id = $request->input('id');
            $server = $request->input('server');
            $level = $request->input('level');

            $user = User::find($id);

            $perm = $user->permissions()->where('server_id', $server);

            // User already has permissions to this server, changing
            if ($perm->count() > 0) {

                $perm = $perm->first();
                $perm->level = $level;
                $perm->save();

                return response()->json(['permission' => $perm]);

            }
            else {
                $srv = Server::find($server);

                $perm = new Permission;
                $perm->server()->associate($srv);
                $perm->level = $level;
                $user->permissions()->save($perm);

                return response()->json(['permission' => $perm]);
            }

        }

    }

    public function take(Request $request) {

        $user = Auth::user();

        if ($user->can('modify-permissions', User::class)) {
            $id = $request->input('id');
            $server = $request->input('server');

            $user = User::find($id);

            $perm = $user->permissions()->where('server_id', $server);

            // User has permissions to this server, taking
            if ($perm->count() > 0) {

                $perm->first()->delete();
                return response()->json(['ok' => true]);

            }
            return response()->json(['ok' => false, 'err' => 'Użytkownik nie posiada serwera.']);

        }

        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);

    }

}
