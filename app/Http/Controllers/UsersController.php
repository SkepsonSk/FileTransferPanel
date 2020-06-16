<?php

namespace App\Http\Controllers;

use App\Power;
use App\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    /**
     * Returns all users except currently logged in user.
     *
     * @return array
     */
    public function users() {
        return User::all()->except(Auth::id());
    }

    /**
     * Returns a current user.
     *
     * @return array
     */
    public function current() {
        return Auth::user();
    }

    /**
     * Returns a view with creator of a new user.
     *
     * @return array
     */
    public function create() {
        $user = Auth::user();

        if ($user->can('create', User::class)) {
            return view('users.create');
        }
    }

    /**
     * Returns a view with editor of user permissions.
     *
     * @return array
     */
    public function permissions($id) {
        $user = Auth::user();

        if ($user->can('modify-permissions', User::class)) {
            return view('users.permissions', ['id' => $id]);
        }
    }

    /**
     * Creates and saves a new user.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request) {

        $user = Auth::user();

        if ($user->can('create', User::class)){
            $email = $request->input('email');
            $name = $request->input('name');
            $password = Hash::make($request->input('password'));

            $user = new User;
            $user->email = $email;
            $user->name = $name;
            $user->password = $password;
            $user->save();

            $power = new Power;
            $power->power = 0;
            $user->power()->save($power);

            return response()->json(['ok' => true, 'href' => route('dashboard')]);
        }

        return response()->json(['ok' => false]);

    }

    /**
     * Deletes an user.
     *
     * @param Request $request
     * @return array
     */
    public function destroy(Request $request) {

        $user = Auth::user();

        if ($user->can('destroy', User::class)){
            $id = $request->input('id');

            $user = User::find($id);
            $user->power()->delete();
            $user->delete();

            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false]);

    }

}
