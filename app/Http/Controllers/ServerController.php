<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerController extends Controller
{

    public function allservers() {
        return Server::all();
    }

    public function servers() {
        $user = Auth::user();
        $perms = $user->permissions()->get();

        $servers = [];
        foreach ($perms as $perm) {
            $server = $perm->server()->first();
            array_push($servers, ['id' => $server->id, 'name' => $server->name, 'start' => $server->path]);
        }

        return response()->json(['servers' => $servers]);
    }

    // -------- -------- --------

    public function create() {
        $user = Auth::user();
        if ($user->can('create', Server::class))
            return view('servers.create');
    }

    public function store(Request $request) {
        $name = $request->input('name');
        $path = $request->input('path');

        $user = Auth::user();
        if ($user->can('create', Server::class)) {
            $server = new Server;
            $server->name = $name;
            $server->path = $path;
            $server->save();

            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false]);
    }

    public function destroy(Request $request) {
        $id = $request->input('id');

        $user = Auth::user();
        if ($user->can('destroy', Server::class)) {

            $server = Server::find($id);
            $server->permissions()->delete();
            $server->delete();

            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false]);
    }

    // -------- -------- --------

    public function files(Request $request) {
        $id = $request->input('id');
        $path = $request->input('path');
        return Server::find($id)->files($path);
    }

    public function move(Request $request) {
        $id = $request->input('id');
        $from = $request->input('from');
        $to = $request->input('to');
        $fileName = $request->input('fileName');
        return Server::find($id)->move($from, $to, $fileName);
    }

    public function remove(Request $request) {
        $id = $request->input('id');
        $file = $request->input('file');
        return Server::find($id)->remove($file);
    }

    public function read(Request $request) {
        $id = $request->input('id');
        $file = $request->input('file');
        return Server::find($id)->read($file);
    }

    public function write(Request $request) {
        $id = $request->input('id');
        $file = $request->input('file');
        $data = $request->input('data');
        return Server::find($id)->write($file, $data);
    }

    public function upload(Request $request) {
        $id = $request->input('id');
        $files = $request->file('files');
        $path = $request->input('path');
        return Server::find($id)->uploadFiles($files, $path);
    }

    public function download(Request $request) {
        $id = $request->input('id');
        $file = $request->input('file');
        $name = $request->input('name');
        return Server::find($id)->download($file, $name);
    }

    public function createFile(Request $request) {
        $id = $request->input('id');
        $name = $request->input('name');
        $path = $request->input('path');
        return Server::find($id)->createFile($name, $path);
    }

    public function createDirectory(Request $request) {
        $id = $request->input('id');
        $name = $request->input('name');
        $path = $request->input('path');
        return Server::find($id)->createDirectory($name, $path);
    }

}
