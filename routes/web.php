<?php

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

Route::get("/cencus-data", "CencusController@index");

Route::get("/import", "ImportController@importFile");
Route::get("/import-1", "ImportController@importCensusFile");

Route::get("/test", "IndexController@hello");
Route::get("/check", "IndexController@insertData");

Auth::routes();

Route::get('/location', 'HomeController@index')->name('home');
