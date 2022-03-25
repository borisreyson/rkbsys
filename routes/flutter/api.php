<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

$masterDomain =  function () {

Route::middleware('api')->get('/',
							[ 'uses'=>'flutter\FlutterController@createIDcard',
								"alias" => 'flutter.create.id.card'
							]);
Route::get('/boris',function(){
	dd("OK");
});
Route::middleware('api')->post('/login', [
        'uses'  => 'flutter\FlutterController@LoginValidate',
        'as'    => 'api.flutter.login'
    ]);

Route::middleware('api')->post('/login/face', [
        'uses'  => 'flutter\FlutterController@loginFace',
        'as'    => 'api.flutter.login.face'
    ]);
Route::middleware('api')->post('/area/save/point', [
        'uses'  => 'flutter\FlutterController@saveMapPoint',
        'as'    => 'api.flutter.saveMapPoint'
    ]);
Route::middleware('api')->post('/area/del/point', [
        'uses'  => 'flutter\FlutterController@delMapPoint',
        'as'    => 'api.flutter.delMapPoint'
    ]);
Route::middleware('api')->post('/area/edit/point', [
        'uses'  => 'flutter\FlutterController@editMapPoint',
        'as'    => 'api.flutter.editMapPoint'
    ]);
Route::middleware('api')->get('/lastAbsen',
                [ 'uses'=>'flutter\FlutterController@lastAbsen',
                  "alias" => 'flutter.user.lastAbsen'
                ]);
Route::middleware('api')->post('/login/abpenergy',
						[ 'uses'=>'android\androidController@LoginValidateNew',
							"alias" => 'abpenergy.login.validate'
						]);
Route::middleware('api')->get('/login/abpenergy',
                        [ 'uses'=>'android\androidController@getUserLogin',
                            "alias" => 'abpenergy.login'
                        ]);
Route::middleware('api')->get('/message/info',
                        [ 'uses'=>'hse\hseController@infoMessage',
                            "alias" => 'abpenergy.infoMessage'
                        ]);

Route::middleware('api')->get('/test/login',
                        [ 'uses'=>'flutter\FlutterController@loginTest',
                            "alias" => 'flutter.login.test'
                        ]);
Route::middleware('api')->get('/test/data/siswa',
                        [ 'uses'=>'flutter\FlutterController@testDataSiswa',
                            "alias" => 'flutter.testDataSiswa'
                        ]);
Route::middleware('api')->post('/test/data/siswa',
                        [ 'uses'=>'flutter\FlutterController@siswaIn',
                            "alias" => 'flutter.test.siswaIn'
                        ]);

Route::middleware('api')->put('/test/data/siswa',
                        [ 'uses'=>'flutter\FlutterController@siswaPutData',
                            "alias" => 'flutter.test.siswaPutData'
                        ]);

Route::middleware('api')->post('/test/data/siswa/update',
                        [ 'uses'=>'flutter\FlutterController@siswaPut',
                            "alias" => 'flutter.test.siswaPut'
                        ]);

Route::middleware('api')->delete('/test/data/siswa',
                        [ 'uses'=>'flutter\FlutterController@siswaDel',
                            "alias" => 'flutter.siswaDel'
                        ]);

Route::middleware('api')->get('/test/data/login',
                        [ 'uses'=>'flutter\FlutterController@testDataLogin',
                            "alias" => 'flutter.testDataLogin'
                        ]);
Route::middleware('api')->post('/test/data/login',
                        [ 'uses'=>'flutter\FlutterController@inTestDataLogin',
                            "alias" => 'flutter.inTestDataLogin'
                        ]);
Route::middleware('api')->put('/test/data/login',
                        [ 'uses'=>'flutter\FlutterController@putTestDataLogin',
                            "alias" => 'flutter.putTestDataLogin'
                        ]);
Route::middleware('api')->delete('/test/data/login',
                        [ 'uses'=>'flutter\FlutterController@loginDel',
                            "alias" => 'flutter.loginDel'
                        ]);
Route::middleware('api')->post('/save/buletin',
                        [ 'uses'=>'flutter\FlutterController@saveBuletin',
                            "alias" => 'flutter.saveBuletin'
                        ]);
Route::middleware('api')->put('/save/buletin',
                        [ 'uses'=>'flutter\FlutterController@updateBuletin',
                            "alias" => 'flutter.updateBuletin'
                        ]);
Route::middleware('api')->put('/sh/buletin',
                        [ 'uses'=>'flutter\FlutterController@showHideBuletin',
                            "alias" => 'flutter.showHideBuletin'
                        ]);
Route::middleware('api')->delete('/save/buletin',
                        [ 'uses'=>'flutter\FlutterController@deleteBuletin',
                            "alias" => 'flutter.deleteBuletin'
                        ]);


};
Route::domain('lp.abpjobsite.com')->group($masterDomain);
