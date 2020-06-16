<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PowersController extends Controller
{

    /**
     * Gets the power of user.
     *
     * @return array
     */
    public function show(Request $request) {
        $id = $request->input('id');
        $user = User::find($id);
        $power = $user->power()->first()->power;
        return response()->json(['power' => $power]);
    }


    /**
     * Saves the power of the user.
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request) {
        $id = $request->input('id');
        $power = $request->input('power');

        $pow = User::find($id)->power()->first();

        $user = Auth::user();
        if ($user->can('modify-powers', User::class)) {
            $pow->power = $power;
            $pow->save();

            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);

    }

}
