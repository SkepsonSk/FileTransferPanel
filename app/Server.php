<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Server extends Model
{

    public function permissions() {
        return $this->hasMany(Permission::class);
    }

    // ----

    public function files($path) {

        $user = Auth::user();

        if ($user->can('view', $this)) {

            if (strpos($path, $this->path) === false)
                return response()->json(['ok' => false, 'err' => 'Niewłaściwa ścieżka.']);

            $location = scandir($path);
            $dir = array('dirs' => [], 'files' => []);

            foreach ($location as $loc) {

                if (Str::startsWith($loc, '.')) continue;

                $type = filetype("$path/$loc");
                if ($type === 'file')
                    array_push($dir['files'], $loc);
                else if ($type === 'dir')
                    array_push($dir['dirs'], $loc);

            }

            return response()->json(['ok' => true, 'files' => $dir]);

//            return $dir;
        }

        return response()->json(['ok' => false, 'err' => 'Odmowa dostępu.']);

    }

    public function move($from, $to, $fileName) {

        $user = Auth::user();

        if ($user->can('move', $this)) {
            if (!file_exists($from))
                return json_encode(array('ok' => false, 'err' => 'from not exists.'));
            else if (!file_exists($to))
                return json_encode(array('ok' => false, 'err' => 'to not exists.'));

            else if (!is_writable($to))
                return json_encode(array('ok' => false, 'err' => 'to not writable.'));

            else {

                copy($from, $to . '/' . $fileName);
                unlink($from);

                return json_encode(array('ok' => true, 'from' => $from));
            }
        }

        return json_encode(array('ok' => false, 'err' => 'Brak uprawnień do przenoszenia plików.'));

    }

    public function remove($file) {

        $user = Auth::user();

        if ($user->can('delete', $this)) {
            if (!file_exists($file))
                return json_encode(array('ok' => false, 'err' => 'file not exists.'));

            else {
                unlink($file);
                return json_encode(array('ok' => true));
            }
        }

        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);

    }

    public function read($fileName) {
        $user = Auth::user();

        if ($user->can('view', $this)) {
            $data = file_get_contents($fileName);
            return json_encode(array('ok' => true, 'file' => $data));
        }

        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);

    }

    public function write($fileName, $data) {
        $user = Auth::user();

        if ($user->can('modify-files', $this)) {
            file_put_contents($fileName, $data);
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false, 'err' => 'Brak uprawnień do modyfikacji plików.']);
    }

    public function download($fileName, $name) {
        $user = Auth::user();

        if ($user->can('download', $this)) {
            $mime = mime_content_type($fileName);
            $headers = ['Content-Type: ' . $mime, 'Content-Disposition: attachment'];

            return response()->download($fileName, $name, $headers);
        }

        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);
    }

    public function uploadFiles($files, $path) {
        $user = Auth::user();

        if ($user->can('upload', $this)){
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $file->move($path, $name);
            }
            return json_encode(array('ok' => true));
        }

        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);

    }

    // -------- -------- -------- --------

    public function createDirectory($name, $path) {
        $user = Auth::user();

        if ($user->can('create-files', $this)) {
            $created = mkdir($path . '/' . $name);
            return response()->json(['ok' => $created]);
        }
        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);

    }

    public function createFile($name, $path) {
        $user = Auth::user();

        if ($user->can('create-files', $this)) {
            file_put_contents($path . '/' . $name, '');
            return response()->json(['ok' => true]);
        }
        return response()->json(['ok' => false, 'err' => 'Brak uprawnień.']);
    }

}
