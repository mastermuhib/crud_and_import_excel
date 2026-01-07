<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware groufdetailp. Now create something great!
|
*/
Route::get('/', function () {
    return view('login');
})->name('index');
Route::get('/login', function () {
    return view('login');
})->name('login');


//signout
Route::get('/logout', 'Logincontroller@logout')->middleware('auth:admin');
Route::post('/login', 'Logincontroller@login');


//member
Route::get('/member/list','MemberController@index')->middleware('auth:admin');
Route::post('/data_member','MemberController@list_data')->middleware('auth:admin');
Route::post('/cek_load_member','MemberController@cek_load_member')->middleware('auth:admin');
Route::post('/post_member','MemberController@post')->middleware('auth:admin');
Route::post('/nonactive/member','MemberController@nonactive')->middleware('auth:admin');
Route::post('/active/member','MemberController@active')->middleware('auth:admin');
Route::post('/delete/member','MemberController@delete')->middleware('auth:admin');
Route::get('/member/detail/{id}','MemberController@detail')->middleware('auth:admin');
Route::post('/update_member','MemberController@update')->middleware('auth:admin');
Route::get('/member/add','MemberController@add')->middleware('auth:admin');
Route::get('/member/import','MemberController@import')->middleware('auth:admin');
Route::post('/post_import_member','MemberController@post_import_member')->middleware('auth:admin');
Route::post('/member/calculate','MemberController@calculate')->middleware('auth:admin');
Route::get('/member/sanding-data','MemberController@pairing')->middleware('auth:admin');
Route::post('/post_pairing_member','MemberController@post_pairing_member')->middleware('auth:admin');
Route::get('/member/download_pairing/{coloumn}','MemberController@download_pairing')->middleware('auth:admin');



