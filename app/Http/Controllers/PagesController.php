<?php

namespace App\Http\Controllers;

use App\Server;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return $this->dashboard();
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $power = $user->power()->first()->power;
            return view('dashboard', ['power' => $power, 'user' => $user->name]);
        }
        else
            return view('login');
    }

    /**
     * Show the application ftp panel.
     *
     * @param $id of a server
     * @return Renderable
     */
    public function ftp($id)
    {
        $server = Server::find($id);
        $start = $server->path;

        return view('ftp', ['id' => $id, 'start' => $start]);
    }

    /**
     * Show the power form panel.
     *
     * @param $id of an user
     * @return Renderable
     */
    public function power($id)
    {
        $user = Auth::user();
        if ($user->can('modify-powers', User::class))
            return view('users.powers', ['id' => $id]);
    }

}
