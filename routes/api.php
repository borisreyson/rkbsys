<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->get('/onlineUser', [
        'uses'  => 'masterContoller@onlineUser',
        'as'    => 'api.onlineUser'
    ]);
Route::middleware('api')->get('/{id_user}.del', [
        'uses'  => 'userController@userdel',
        'as'    => 'api.userDel'
    ]);

Route::middleware('api')->post('/department', [
        'uses'	=> 'sysController@department',
        'as'	=> 'api.department'
    ]);
Route::middleware('api')->post('/expired', [
        'uses'	=> 'sysController@expired_rkb',
        'as'	=> 'api.expired_rkb'
    ]);
Route::middleware('api')->post('/expired/send', [
        'uses'  => 'sysController@expired_send',
        'as'    => 'api.expired_send'
    ]);
Route::middleware('api')->post('/tmp/edit', [
        'uses'  => 'v1\rkbController@edit_tmp',
        'as'    => 'api.edit.tmp'
    ]);
Route::middleware('api')->get('/satuan', [
        'uses'  => 'v1\rkbController@satuan',
        'as'    => 'api.satuan'
    ]);
Route::middleware('api')->post('/delete/entry', [
        'uses'  => 'v1\rkbController@delete_entry',
        'as'    => 'api.delete.entry'
    ]);
Route::middleware('api')->post('/delete/rkb', [
        'uses'  => 'v1\rkbController@rkb_delete',
        'as'    => 'api.delete.rkb_delete'
    ]);
Route::middleware('api')->post('/rkb/close.rkb', [
        'uses'  => 'v2\rkbController@close_rkb',
        'as'    => 'api.close.rkb'
    ]);
Route::middleware('api')->post('/rkb/close.rkb.cancel', [
        'uses'  => 'v2\rkbController@close_rkb_cancel',
        'as'    => 'api.close.close_rkb_cancel'
    ]);
Route::middleware('api')->put('/rkb/close.rkb.cancel', [
        'uses'  => 'v2\rkbController@close_rkb_cancel_put',
        'as'    => 'api.close.close_rkb_cancel_put'
    ]);
Route::middleware('api')->put('/rkb/close.rkb', [
        'uses'  => 'v2\rkbController@close_rkb_send',
        'as'    => 'api.close.rkb.send'
    ]);