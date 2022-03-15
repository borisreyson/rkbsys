<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::middleware('api')->post('/android/login/validate', [
        'uses'  => 'api\AndroidApiController@LoginValidate',
        'as'    => 'api.android.login.validate'
    ]);
Route::middleware('api')->get('/android/api/check', [
        'uses'  => 'api\AndroidApiController@checkApi',
        'as'    => 'api.android.checkApi'
    ]);
Route::middleware('api')->get('/android/get/rkb/user', [
        'uses'  => 'api\AndroidApiController@rkbUser',
        'as'    => 'api.android.rkbUser'
    ]);
Route::middleware('api')->get('/android/get/rkb/admin', [
        'uses'  => 'api\AndroidApiController@rkbAdmin',
        'as'    => 'api.android.rkbAdmin'
    ]);

Route::middleware('api')->get('/android/get/rkb/detail', [
        'uses'  => 'api\AndroidApiController@rkbDetail',
        'as'    => 'api.android.rkbDetail'
    ]);


Route::middleware('api')->post('/android/post/rkb/kabag/approve', [
        'uses'  => 'api\AndroidApiController@rkbKabagApprove',
        'as'    => 'api.android.rkbKabagApprove'
    ]);

Route::middleware('api')->post('/android/post/rkb/ktt/approve', [
        'uses'  => 'api\AndroidApiController@approveKTT',
        'as'    => 'api.android.approveKTT'
    ]);
Route::middleware('api')->post('/android/post/login-face-id', [
        'uses'  => 'api\AndroidApiController@loginFaceId',
        'as'    => 'api.android.loginFaceId'
    ]);

Route::middleware('api')->post('/android/post/updatePasswordFace', [
        'uses'  => 'api\AndroidApiController@updatePasswordFaceid',
        'as'    => 'api.android.updatePasswordFaceid'
    ]);


Route::middleware('api')->get('/android/get/list/absen', [
        'uses'  => 'api\AndroidApiController@listAbsen',
        'as'    => 'api.android.listAbsen'
    ]);

Route::middleware('api')->get('/android/send/notification', [
        'uses'  => 'api\FirebaseController@sendNotification',
        'as'    => 'api.android.sendNotification'
    ]);
Route::middleware('api')->get('/android/send/notification/faceid', [
        'uses'  => 'api\FirebaseController@sendNotificationFaceId',
        'as'    => 'api.android.sendNotification.faceid'
    ]);

Route::middleware('api')->get('/android/app/version', 
                function(){
                    return array("version"=>"1.9");
                }
                );
Route::middleware('api')->get('/android/app/cek/lokasi', 
                function(){
                    return array("area"=>"abp","jam"=>date("H"),"menit"=>date("i"),"detik"=>date("s"));
                }
                );

Route::middleware('api')->get('/android/app/version', 
                function(Request $request){
                    $app = DB::table("keamanan.app_version");
                    if($request->app=="abp_energy")
                    {
                        $r = $app->where("app",$request->app)->first();
                        return array("version"=>$r->version,"app"=>$r->version,"url"=>$r->url); 
                    }else if($request->app=="face")
                    {
                        $r = $app->where("app",$request->app)->first();
                        return array("version"=>$r->version,"app"=>$r->version,"url"=>$r->url); 
                    }
                   
                }
                );

Route::middleware('api')->get('/android/app/download', 
                function(Request $request){
                    if($request->app=="abp_energy")
                    {
                        return redirect('/apk/abp_energy.apk');
                    }else if($request->app=="face")
                    {
                        return redirect('/apk/face_abp.apk');
                    }                   
                }
                );
Route::middleware('api')->post('/android/api/cancel/rkb', [
        'uses'  => 'api\AndroidController@cancel_rkb_post',
        'as'    => 'api.android.cancel_rkb_post'
    ]);
Route::middleware('api')->get('/android/sarpras/user', [
        'uses'  => 'api\AndroidController@getSarprasUser',
        'as'    => 'api.android.sarpras.getSarprasUser'
    ]);
Route::middleware('api')->get('/android/sarpras/all', [
        'uses'  => 'api\AndroidController@getSarprasAll',
        'as'    => 'api.android.sarpras.getSarprasAll'
    ]);
Route::middleware('api')->get('/android/sarpras/user/lihat', [
        'uses'  => 'api\AndroidController@getSarprasUserDetail',
        'as'    => 'api.android.sarpras.getSarprasUserDetail'
    ]);
Route::middleware('api')->get('/android/token/firebase', [
        'uses'  => 'absen\absenController@faceidToken',
        'as'    => 'api.android.faceidToken'
    ]);
Route::middleware('api')->post('/android/post/kirim/masukan', [
        'uses'  => 'absen\absenController@kirimMasukan',
        'as'    => 'api.android.kirimMasukan'
    ]);
Route::middleware('api')->get('/android/get/absen/log', [
        'uses'  => 'absen\absenController@absenLog',
        'as'    => 'api.android.absenLog'
    ]);
Route::middleware('api')->get('/android/check/folder', [
        'uses'  => 'absen\absenController@checkFolder',
        'as'    => 'api.android.checkFolder'
    ]);

Route::middleware('api')->get('/android/update/phone/token', [
        'uses'  => 'absen\absenController@updatePhoneToken',
        'as'    => 'api.android.updatePhoneToken'
    ]);

Route::middleware('api')->post('/android/token/firebase/new', [
        'uses'  => 'absen\absenController@faceidTokenNew',
        'as'    => 'api.android.faceidTokenNew'
    ]);

Route::middleware('api')->get('/android/token/firebase/new', [
        'uses'  => 'absen\absenController@faceidTokenNew',
        'as'    => 'api.android.faceidTokenNew'
    ]);
Route::middleware('api')->get('/android/firebase/abpenergy', [
        'uses'  => 'api\FirebaseController@sendNotificationABPSYSTEM',
        'as'    => 'api.android.abpenergy.notification'
    ]);

Route::middleware('api')->get('/abpenergy/create/notification', [
        'uses'  => 'android\notifController@create_notification_hazard_verify',
        'as'    => 'api.android.abpenergy.create.notification'
    ]);
Route::middleware('api')->get('/abpenergy/send/notification/kepada', [
        'uses'  => 'android\notifController@notifKepada',
        'as'    => 'api.android.abpenergy.send.notifKepada'
    ]);
Route::middleware('api')->get('/abpenergy/hazard/verify', [
        'uses'  => 'android\notifController@hazardSetujui',
        'as'    => 'api.android.abpenergy.hazard.notification.safety'
    ]);

Route::middleware('api')->post('/abpenergy/update/token', [
        'uses'  => 'api\AndroidApiController@updateTokenAbpEnergy',
        'as'    => 'api.android.abpenergy.update.token'
    ]);

Route::middleware('api')->get('/abpenergy/tenggat/notification', [
        'uses'  => 'android\notifController@tenggatHazard',
        'as'    => 'api.android.abpenergy.tenggat.notification'
    ]);
Route::middleware('api')->get('/abpenergy/tenggat/users', [
        'uses'  => 'android\notifController@tenggatUser',
        'as'    => 'api.android.abpenergy.tenggat.tenggatUser'
    ]);

Route::middleware('api')->get('/abpenergy/tenggat/notification/group', [
        'uses'  => 'android\notifController@notificationGroup',
        'as'    => 'api.android.abpenergy.tenggat.notificationGroup'
    ]);

Route::middleware('api')->get('/abpenergy/send/notification/to', [
        'uses'  => 'android\notifController@notifto',
        'as'    => 'api.android.abpenergy.tenggat.notifto'
    ]);


