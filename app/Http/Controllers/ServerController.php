<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{

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

    public function upload(Request $request) {
        $id = $request->input('id');
        $files = $request->file('files');
        $path = $request->input('path');
        return Server::find($id)->uploadFiles($files, $path);
    }

}
