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

//Route::get('/', ['as' => 'home', 'uses' => 'MainController@index'] );
//Route::get('/', 'PayController@index');
Route::get('/admin/52201112', 'PayController@index');
Route::get('/', function(){
    return view("main");
});
Route::get('/statement', 'PayController@index');


Route::get('/pay/store', 'PayController@store');
Route::post('/pay/timeCountSetting', 'PayController@timeCountSetting');
Route::get('/pay/calenderData', 'PayController@calenderData');
