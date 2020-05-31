<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false
]);

Route::post('/files', 'ServerController@files')->name('files');
Route::post('/move', 'ServerController@move')->name('move');
Route::post('/remove', 'ServerController@remove')->name('remove');
Route::post('/read', 'ServerController@read')->name('read');
Route::post('/upload', 'ServerController@upload')->name('upload');

Route::get('/home', 'HomeController@index')->name('home');
