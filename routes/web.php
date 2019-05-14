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
//对称加密
Route::get('test', 'Test\TestController@test');
//非对称加密
Route::get('innt', 'Test\TestController@innt');
//验签
Route::get('tens', 'Test\TestController@tens');
//5.13模拟注册
Route::get('regis', 'Net\NetController@regis');
Route::post('neet', 'Net\NetController@neet');
//5.13模拟登录
Route::post('login', 'Net\NetController@login');
Route::get('logins', 'Net\NetController@logins');

