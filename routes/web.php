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

/*
 * Basic pages navigation
 */

Route::get('/', 'PagesController@index')->name('index');
Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');
Route::get('/ftp/{server}', 'PagesController@ftp')->name('ftp');
Route::get('/users/{id}/power', 'PagesController@power')->name('users.power.get');

Auth::routes([
    'register' => false
]);

Route::post('/servers', 'ServerController@servers')->name('servers');
Route::post('/allservers', 'ServerController@allservers')->name('allservers');
Route::post('/files', 'ServerController@files')->name('files');

Route::post('/users', 'UsersController@users')->name('users');
Route::post('/user', 'UsersController@user')->name('user');
Route::post('/users/current', 'UsersController@current')->name('user.current');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::post('/users/store', 'UsersController@store')->name('users.create');
Route::get('/users/{id}/permissions', 'UsersController@permissions')->name('users.permissions');
Route::post('/users/permissions', 'PermissionsController@permissions')->name('users.permissions.get');
Route::post('/users/permissions/give', 'PermissionsController@give')->name('users.permissions.give');
Route::post('/users/power', 'PowersController@show')->name('users.power.get');
Route::post('/users/power/update', 'PowersController@update')->name('users.power.update');
Route::post('/users/destroy', 'PowersController@destroy')->name('users.destroy');

Route::get('/servers/create', 'ServerController@create')->name('servers.create');
Route::post('/servers/store', 'ServerController@store')->name('servers.store');
Route::post('/servers/destroy', 'ServerController@destroy')->name('servers.destroy');

/*
 * FTP editor routes
 */

Route::post('/move', 'ServerController@move')->name('move');
Route::post('/remove', 'ServerController@remove')->name('remove');
Route::post('/read', 'ServerController@read')->name('read');
Route::post('/write', 'ServerController@write')->name('write');
Route::post('/upload', 'ServerController@upload')->name('upload');
Route::post('/createfile', 'ServerController@createFile')->name('createfile');
Route::post('/createdirectory', 'ServerController@createDirectory')->name('createdirectory');
Route::post('/download', 'ServerController@download')->name('download');

