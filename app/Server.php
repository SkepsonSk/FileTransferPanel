<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Server extends Model
{

    public function files($path) {

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

        return $dir;

    }

    public function move($from, $to, $fileName) {

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

    public function remove($file) {

        if (!file_exists($file))
            return json_encode(array('ok' => false, 'err' => 'file not exists.'));

        else {
            unlink($file);
            return json_encode(array('ok' => true));
        }

    }

    public function read($fileName) {
        $file = fopen($fileName, "r");
        $data = array();

        while (!feof($file))
            array_push($data, fgets($file));

        fclose($file);
        return json_encode(array('ok' => true, 'file' => $data));

    }

    public function writeToFile($file) {

    }

    public function uploadFiles($files, $path) {

        foreach ($files as $file) {

            $name = $file->getClientOriginalName();
            $file->move($path, $name);

        }
        return json_encode(array('ok' => true));
    }

}
