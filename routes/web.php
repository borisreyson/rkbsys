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
//index
Route::get('/', [ 'uses'=>'masterController@index',
				  "alias" => 'rkb.index'
				]);
//index
//HOMEPAGE


Route::get('/home', [ 'uses'=>'masterController@mainpage',
				  "alias" => 'rkb.home'
				]);
//HOMEPAGE

Route::get('/refresh-csrf', function(){
							    return array("csrf_token"=>csrf_token());
							});
//login
Route::post('/login', 'userController@login');
Route::post('/login/v1', 'userController@login_v1');
Route::get('/logout', 'userController@logout');
Route::get('/cookie/login', 'userController@CookieLogin');


//login

//rkb get
Route::get('/rkb', 'rkbController@rkb');
Route::get('/rkb/expired', 'rkbController@rkb');
Route::post('/rkb/detail.py', 'rkbController@detail_rkb');
//rkb post

//FORM RKB V1

Route::get('/v1/recent-image-{id_rkb}','v1\rkbController@recentIMG');
//DELETE V1
Route::post('/v1/soft-delete','v1\rkbController@soft_delete');
Route::post('/v1/fast-delete','v1\rkbController@fast_delete');

//Temp RKB

Route::post('/v1/create/rkb','rkbController@create_rkb');
Route::get('/v1/temperory-rkb','v1\rkbController@temp_rkb');
Route::get('/v1/edit-all',[
							"uses"  => 'v1\rkbController@edit_all',
							"alias" => 'tmp.edit.all'
						]);
Route::post('v1/update-tmp',[
								"uses"  => 'v1\rkbController@update_tmp',
								"alias" => 'tmp.update'
							]);
Route::post('/v1/rkb/update','v1\rkbController@update_all');

Route::post('/rkb', 'rkbController@rkb');
Route::post('/rkb/expired', 'rkbController@rkb');
Route::post('/e_rkb_temp.delete', 'rkbController@del_rkb_temp_all');
Route::post('/e_rkb_temp', 'rkbController@e_rkb_temp');
Route::post('/e_rkb_temp/{no_rkb}', 'rkbController@up_rkb_temp');
Route::post('/create_rkb', 'rkbController@create_rkb');

//master dept get
Route::get('/dept', 'masterController@dept');

//master section get

Route::get('/sect', 'masterController@sect');

//master satuan get
Route::get('/satuan', 'masterController@satuan');

//modal /rkb/cancel/item get
Route::get('/rkb/detail/files/view-{img_name}','modalController@files_view');
Route::get('/rkb/detail/files/penawaran/view-{img_name}','modalController@files_view');
Route::get('/rkb/detail/gambar/delete-{img_name}','modalController@delete_gambar');
Route::get('/rkb/detail/files/delete-{img_name}','modalController@delete_file');

//modal /rkb/cancel/item post
Route::post('/rkb/cancel/item/setRemarks','modalController@setRemaks');
Route::post('/rkb/cancel/item','modalController@cancel_item');
Route::post('/rkb/detail/files','modalController@files_item');
Route::post('/rkb/detail/pictures','modalController@pictures');
Route::post('/rkb/cancel-rkb.py','modalController@cancel_rkb');
Route::post('/rkb/cancel-rkb.submit','modalController@cancel_rkb_post');

//master user get
Route::get('/user', 'userController@user');
Route::get('/level/user', 'userController@level_user');
Route::get('/level/{level}.html', 'userController@level_edit');
Route::post('/level/{level}.html', 'userController@level_update');
Route::get('/level/{level}.del', 'userController@level_del');

//combo box
Route::get('/section-from-dept', 'masterController@section');

//approve rkb

//*KABAG
Route::get('/approve/rkb/{no_rkb}', 'rkbController@approveKABAG');

//*KTT

Route::get('/approve/rkb/ktt/{no_rkb}', 'rkbController@approveKTT');

//Conver To PO
Route::get('/convert/{no_rkb}.PO', 'poController@ConvertRkb');

//printRkb
Route::get('/printRkb', 'rkbController@printRkb');
Route::get('/rkbPrint', 'rkbController@rkbPrint');

Route::get('/kabag/rkbPrint', 'kabagController@printRkb');
Route::get('/kabag/printRkb', 'kabagController@rkbPrint');

Route::get('/ktt/rkbPrint', 'kttController@printRkb');
Route::get('/ktt/printRkb', 'kttController@rkbPrint');

Route::get('/logistic/rkbPrint', 'poController@printRkb');
Route::get('/logistic/printRkb', 'poController@rkbPrint');

Route::get('/purchasing/upload-penawaran/{no_rkb}/{part_name}', 'poController@upload_penawaran');

Route::post('/purchasing/upload-penawaran/{no_rkb}/{part_name}', 'poController@post_penawaran');


Route::put('/purchasing/penawaran/replace', 'poController@replace_send');
Route::delete('/purchasing/penawaran/delete', 'poController@delete_penawaran');
Route::post('/purchasing/penawaran/replace', 'poController@replace');
Route::post('/purchasing/edit/qty', 'poController@edit_qty');
Route::put('/purchasing/update/qty', 'poController@update_qty');

Route::get('/print-preview-{f_name}', 'sysController@print_preview');
Route::get('/print-preview-{f_name}.pdf', 'sysController@print_preview');


Route::get('/send/email',"rkbController@AdminSend");
Route::get('/view/{view}',"rkbController@viewTemplate");

Route::get('/rkb/close',"rkbController@rkb");
//inbox
Route::get('/get/username',"sysController@username");
Route::get('/get/nomor/rkb',"sysController@get_norkb");
Route::get('/get/part/name',"sysController@get_partname");
Route::get('/get/part/number',"sysController@get_partnumber");

//notif
Route::post('/notif/open',"sysController@notif_open");
//inbox user
Route::get('/inbox',"sysController@inbox");
Route::get('/inbox/{id_pesan}.message',"sysController@inbox1");

Route::post('/inbox/',"sysController@send");
Route::post('/inbox/{id_pesan}.message',"sysController@send1");

//SENT USER
Route::get('/sent',"sysController@sent");
Route::get('/sent/{id_pesan}.message',"sysController@sentOpen");


Route::get('/v3/rkb',[
				  "uses"	=>	'v2\rkbController@rkb',
				  "alias" 	=> 'v2.rkb'
					]);
Route::post('/v3/rkb',[
				  "uses"	=>	'v2\rkbController@rkb',
				  "alias" 	=> 'v2.rkb.post'
					]);

Route::get('/test/json',[
				  "uses"	=>	'sysController@json_user',
				  "alias" 	=> 'json_user'
					]);
Route::post('/v3/rkb/upload',[
				  "uses"	=>	'v2\rkbController@form_upload',
				  "alias" 	=> 'v2.form_upload'
					]);

//OB

Route::get('/ob/daily',[
					"uses" => "monitorController@ob",
					"alias"=> "monitor.ob.daily"
				]);

Route::get('/ob/monthly',[
					"uses" => "monitorController@obMonthly",
					"alias"=> "monitor.ob.monthly"
				]);
Route::get('/ob/ach',[
					"uses" => "monitorController@obACH",
					"alias"=> "monitor.ob.ACH"
				]);

//HAULING

Route::get('/hauling/daily',[
					"uses" => "monitorController@hauling",
					"alias"=> "monitor.haul.daily"
				]);

Route::get('/hauling/monthly',[
					"uses" => "monitorController@haulMonthly",
					"alias"=> "monitor.haul.monthly"
				]);
Route::get('/hauling/ach',[
					"uses" => "monitorController@haulACH",
					"alias"=> "monitor.haul.ACH"
				]);

//CRUSHING

Route::get('/crushing/daily',[
					"uses" => "monitorController@crushing",
					"alias"=> "monitor.crushing.daily"
				]);

Route::get('/crushing/monthly',[
					"uses" => "monitorController@crushMonthly",
					"alias"=> "monitor.crushing.monthly"
				]);
Route::get('/crushing/ach',[
					"uses" => "monitorController@crushACH",
					"alias"=> "monitor.crushing.ACH"
				]);
//BARGING

Route::get('/barging/daily',[
					"uses" => "monitorController@barging",
					"alias"=> "monitor.barging.daily"
				]);

Route::get('/barging/monthly',[
					"uses" => "monitorController@bargeMonthly",
					"alias"=> "monitor.barging.monthly"
				]);
Route::get('/barging/ach',[
					"uses" => "monitorController@bargeACH",
					"alias"=> "monitor.barging.ACH"
				]);

//boat
Route::get('/boat',[
					"uses" => "monitorController@boat",
					"alias"=> "monitor.boat"
				]);
//stockProduct
Route::get('/stockProduct',[
					"uses" => "monitorController@stockProduct",
					"alias"=> "monitor.stockProduct"
				]);



//FORM MONITORING

Route::get('/monitoring/form/ob',[
					"uses" => "monitorController@formOB",
					"alias"=> "monitor.formOB"
				]);
Route::get('/monitoring/form/ob/q-{dataID}',[
					"uses" => "monitorController@editOB",
					"alias"=> "monitor.editOB"
				]);
Route::post('/monitoring/form/ob/q-{dataID}',[
					"uses" => "monitorController@updateOB",
					"alias"=> "monitor.updateOB"
				]);
Route::get('/monitoring/form/ob/delete-{dataID}',[
					"uses" => "monitorController@deleteOB",
					"alias"=> "monitor.deleteOB"
				]);
Route::get('/monitoring/form/ob/undo-{dataID}',[
					"uses" => "monitorController@undoOB",
					"alias"=> "monitor.undoOB"
				]);

Route::post('/monitoring/form/ob',[
					"uses" => "monitorController@postOB",
					"alias"=> "monitor.postOB"
				]);
//FORM HAULING

Route::get('/monitoring/form/hauling',[
					"uses" => "monitorController@formHAULING",
					"alias"=> "monitor.formHAULING"
				]);
Route::get('/monitoring/form/hauling/q-{dataID}',[
					"uses" => "monitorController@editHAULING",
					"alias"=> "monitor.editHAULING"
				]);
Route::post('/monitoring/form/hauling/q-{dataID}',[
					"uses" => "monitorController@updateHAULING",
					"alias"=> "monitor.updateHAULING"
				]);
Route::get('/monitoring/form/hauling/delete-{dataID}',[
					"uses" => "monitorController@deleteHAULING",
					"alias"=> "monitor.deleteHAULING"
				]);
Route::get('/monitoring/form/hauling/undo-{dataID}',[
					"uses" => "monitorController@undoHAULING",
					"alias"=> "monitor.undoHAULING"
				]);
Route::post('/monitoring/form/hauling',[
					"uses" => "monitorController@postHAULING",
					"alias"=> "monitor.postHAULING"
				]);
//FORM CRUSHING

Route::get('/monitoring/form/crushing',[
					"uses" => "monitorController@formCRUSHING",
					"alias"=> "monitor.formCRUSHING"
				]);
Route::get('/monitoring/form/crushing/q-{dataID}',[
					"uses" => "monitorController@editCRUSHING",
					"alias"=> "monitor.editCRUSHING"
				]);
Route::post('/monitoring/form/crushing/q-{dataID}',[
					"uses" => "monitorController@updateCRUSHING",
					"alias"=> "monitor.updateCRUSHING"
				]);
Route::get('/monitoring/form/crushing/delete-{dataID}',[
					"uses" => "monitorController@deleteCRUSHING",
					"alias"=> "monitor.deleteCRUSHING"
				]);
Route::get('/monitoring/form/crushing/undo-{dataID}',[
					"uses" => "monitorController@undoCRUSHING",
					"alias"=> "monitor.undoCRUSHING"
				]);
Route::post('/monitoring/form/crushing',[
					"uses" => "monitorController@postCRUSHING",
					"alias"=> "monitor.postCRUSHING"
				]);

//FORM BARGING
Route::get('/monitoring/form/barging',[
					"uses" => "monitorController@formBarging",
					"alias"=> "monitor.formBarging"
				]);
Route::get('/monitoring/form/barging/q-{dataID}',[
					"uses" => "monitorController@editBARGING",
					"alias"=> "monitor.editBARGING"
				]);
Route::post('/monitoring/form/barging/q-{dataID}',[
					"uses" => "monitorController@updateBARGING",
					"alias"=> "monitor.updateBARGING"
				]);
Route::get('/monitoring/form/barging/delete-{dataID}',[
					"uses" => "monitorController@deleteBARGING",
					"alias"=> "monitor.deleteBARGING"
				]);
Route::get('/monitoring/form/barging/undo-{dataID}',[
					"uses" => "monitorController@undoBARGING",
					"alias"=> "monitor.undoBARGING"
				]);
Route::post('/monitoring/form/barging',[
					"uses" => "monitorController@postBarging",
					"alias"=> "monitor.postBarging"
				]);

//BOAT
Route::get('/monitoring/form/boat',[
					"uses" => "monitorController@formBOAT",
					"alias"=> "monitor.formBOAT"
				]);
Route::post('/monitoring/form/boat',[
					"uses" => "monitorController@postBOAT",
					"alias"=> "monitor.postBOAT"
				]);

Route::get('/monitoring/form/boat/q-{dataID}',[
					"uses" => "monitorController@editBOAT",
					"alias"=> "monitor.editBOAT"
				]);
Route::post('/monitoring/form/boat/q-{dataID}',[
					"uses" => "monitorController@updateBOAT",
					"alias"=> "monitor.updateBOAT"
				]);
Route::get('/monitoring/form/boat/delete-{dataID}',[
					"uses" => "monitorController@deleteBOAT",
					"alias"=> "monitor.deleteBOAT"
				]);
Route::get('/monitoring/form/boat/undo-{dataID}',[
					"uses" => "monitorController@undoBOAT",
					"alias"=> "monitor.undoBOAT"
				]);
//STOCK
Route::get('/monitoring/form/stock',[
					"uses" => "monitorController@formSTOCK",
					"alias"=> "monitor.formSTOCK"
				]);
Route::post('/monitoring/form/stock',[
					"uses" => "monitorController@postSTOCK",
					"alias"=> "monitor.postSTOCK"
				]);

Route::get('/monitoring/form/stock/q-{dataID}',[
					"uses" => "monitorController@editSTOCK",
					"alias"=> "monitor.editSTOCK"
				]);
Route::post('/monitoring/form/stock/q-{dataID}',[
					"uses" => "monitorController@updateSTOCK",
					"alias"=> "monitor.updateSTOCK"
				]);
Route::get('/monitoring/form/stock/delete-{dataID}',[
					"uses" => "monitorController@deleteSTOCK",
					"alias"=> "monitor.deleteSTOCK"
				]);
Route::get('/monitoring/form/stock/undo-{dataID}',[
					"uses" => "monitorController@undoSTOCK",
					"alias"=> "monitor.undoSTOCK"
				]);


//Delay Hauling
Route::get('/monitoring/form/delay/hauling',[
					"uses" => "monitorController@formDLhauling",
					"alias"=> "monitor.formDLhauling"
				]);
Route::post('/monitoring/form/delay/hauling',[
					"uses" => "monitorController@postDLhauling",
					"alias"=> "monitor.postDLhauling"
				]);

Route::get('/monitoring/form/delay/hauling/q-{dataID}',[
					"uses" => "monitorController@editDLhauling",
					"alias"=> "monitor.editSTOCK"
				]);
Route::post('/monitoring/form/delay/hauling/q-{dataID}',[
					"uses" => "monitorController@updateDLhauling",
					"alias"=> "monitor.updateDLhauling"
				]);
Route::get('/monitoring/form/delay/hauling/delete-{dataID}',[
					"uses" => "monitorController@deleteDLhauling",
					"alias"=> "monitor.deleteDLhauling"
				]);
Route::get('/monitoring/form/delay/hauling/undo-{dataID}',[
					"uses" => "monitorController@undoDLhauling",
					"alias"=> "monitor.undoDLhauling"
				]);
Route::get('/hauling/delay',[
					"uses" => "monitorController@DelayHauling",
					"alias"=> "monitor.DelayHauling"
				]);

//Delay Barging

Route::get('/monitoring/form/delay/barging',[
					"uses" => "monitorController@formDLBarging",
					"alias"=> "monitor.formDLBarging"
				]);
Route::post('/monitoring/form/delay/barging',[
					"uses" => "monitorController@postDLBarging",
					"alias"=> "monitor.postDLBarging"
				]);

Route::get('/monitoring/form/delay/barging/q-{dataID}',[
					"uses" => "monitorController@editDLBarging",
					"alias"=> "monitor.editDLBarging"
				]);
Route::post('/monitoring/form/delay/barging/q-{dataID}',[
					"uses" => "monitorController@updateDLBarging",
					"alias"=> "monitor.updateDLBarging"
				]);
Route::get('/monitoring/form/delay/barging/delete-{dataID}',[
					"uses" => "monitorController@deleteDLBarging",
					"alias"=> "monitor.deleteDLhauling"
				]);
Route::get('/monitoring/form/delay/barging/undo-{dataID}',[
					"uses" => "monitorController@undoDLBarging",
					"alias"=> "monitor.undoDLBarging"
				]);


Route::get('/barging/delay',[
					"uses" => "monitorController@DelayBarging",
					"alias"=> "monitor.DelayBarging"
				]);

//DELAY OB


Route::get('/monitoring/form/delay/ob',[
					"uses" => "monitorController@formDLob",
					"alias"=> "monitor.formDLob"
				]);
Route::post('/monitoring/form/delay/ob',[
					"uses" => "monitorController@postDLob",
					"alias"=> "monitor.postDLob"
				]);
Route::get('/monitoring/form/delay/ob/q-{dataID}',[
					"uses" => "monitorController@editDLob",
					"alias"=> "monitor.editDLob"
				]);
Route::post('/monitoring/form/delay/ob/q-{dataID}',[
					"uses" => "monitorController@updateDLob",
					"alias"=> "monitor.updateDLob"
				]);
Route::get('/monitoring/form/delay/ob/delete-{dataID}',[
					"uses" => "monitorController@deleteDLob",
					"alias"=> "monitor.deleteDLob"
				]);
Route::get('/monitoring/form/delay/ob/undo-{dataID}',[
					"uses" => "monitorController@undoDLbo",
					"alias"=> "monitor.undoDLbo"
				]);


Route::get('/ob/delay',[
					"uses" => "monitorController@DelayOb",
					"alias"=> "monitor.DelayOb"
				]);


//DELAY CRUSHING
Route::get('/monitoring/form/delay/crushing',[
					"uses" => "monitorController@formDLCrushing",
					"alias"=> "monitor.formDLCrushing"
				]);

Route::post('/monitoring/form/delay/crushing',[
					"uses" => "monitorController@postDLCrushing",
					"alias"=> "monitor.postDLCrushing"
				]);

Route::get('/monitoring/form/delay/crushing/q-{dataID}',[
					"uses" => "monitorController@editDLCrushing",
					"alias"=> "monitor.editDLCrushing"
				]);
Route::post('/monitoring/form/delay/crushing/q-{dataID}',[
					"uses" => "monitorController@updateDLCrushing",
					"alias"=> "monitor.updateDLCrushing"
				]);
Route::get('/monitoring/form/delay/crushing/delete-{dataID}',[
					"uses" => "monitorController@deleteDLCrushing",
					"alias"=> "monitor.updateDLCrushing"
				]);
Route::get('/monitoring/form/delay/crushing/undo-{dataID}',[
					"uses" => "monitorController@undoDLCrushing",
					"alias"=> "monitor.updateDLCrushing"
				]);


Route::get('/crushing/delay',[
					"uses" => "monitorController@DelayCrushing",
					"alias"=> "monitor.DelayCrushing"
				]);



//MHU ABP

//FORM HAULING MHU ABP

Route::get('/mhu/form/hauling',[
					"uses" => "monitorController@mhuHauling",
					"alias"=> "monitor.mhuHauling"
				]);
Route::get('/mhu/form/hauling/q-{dataID}',[
					"uses" => "monitorController@edit_mhu_HAULING",
					"alias"=> "monitor.edit_mhu_HAULING"
				]);
Route::post('/mhu/form/hauling/q-{dataID}',[
					"uses" => "monitorController@update_mhu_HAULING",
					"alias"=> "monitor.update_mhu_HAULING"
				]);
Route::get('/mhu/form/hauling/delete-{dataID}',[
					"uses" => "monitorController@delete_mhu_HAULING",
					"alias"=> "monitor.delete_mhu_HAULING"
				]);
Route::get('/mhu/form/hauling/undo-{dataID}',[
					"uses" => "monitorController@undo_mhu_HAULING",
					"alias"=> "monitor.undo_mhu_HAULING"
				]);
Route::get("/mhu/monitoring/form/hauling/check",[
					"uses" => "monitorController@cek_hl_mhu",
					"alias"=> "monitor.cek_hl_mhu"
				]);
Route::post('/mhu/form/hauling',[
					"uses" => "monitorController@mhuHauling_POST",
					"alias"=> "monitor.mhuHauling_POST"
				]);


//FORM Chrushing MHU ABP

Route::get('/mhu/form/crushing',[
					"uses" => "monitorController@mhuCrushing",
					"alias"=> "monitor.mhuCrushing"
				]);
Route::get('/mhu/form/crushing/q-{dataID}',[
					"uses" => "monitorController@edit_mhu_Crushing",
					"alias"=> "monitor.edit_mhu_Crushing"
				]);
Route::post('/mhu/form/crushing/q-{dataID}',[
					"uses" => "monitorController@update_mhu_Crushing",
					"alias"=> "monitor.update_mhu_HAULING"
				]);
Route::get('/mhu/form/crushing/delete-{dataID}',[
					"uses" => "monitorController@delete_mhu_Crushing",
					"alias"=> "monitor.delete_mhu_Crushing"
				]);
Route::get('/mhu/form/crushing/undo-{dataID}',[
					"uses" => "monitorController@undo_mhu_Crushing",
					"alias"=> "monitor.undo_mhu_Crushing"
				]);
Route::get("/mhu/monitoring/form/crushing/check",[
					"uses" => "monitorController@cek_cr_mhu",
					"alias"=> "monitor.cek_cr_mhu"
				]);
Route::post('/mhu/form/crushing',[
					"uses" => "monitorController@mhuCrushing_POST",
					"alias"=> "monitor.mhuCrushing_POST"
				]);

//FORM Barging MHU ABP
Route::group(['prefix' => '/mhu/form/barging'], function () {
	Route::get('/',[
						"uses" => "monitorController@mhuBarging",
						"alias"=> "monitor.mhuBarging"
					]);
	Route::get('/q-{dataID}',[
						"uses" => "monitorController@edit_mhu_Barging",
						"alias"=> "monitor.edit_mhu_Barging"
					]);
	Route::post('/q-{dataID}',[
						"uses" => "monitorController@update_mhu_Barging",
						"alias"=> "monitor.update_mhu_Barging"
					]);
	Route::get('/delete-{dataID}',[
						"uses" => "monitorController@delete_mhu_Barging",
						"alias"=> "monitor.delete_mhu_Barging"
					]);
	Route::get('/undo-{dataID}',[
						"uses" => "monitorController@undo_mhu_Barging",
						"alias"=> "monitor.undo_mhu_Barging"
					]);
	Route::get("/barging/check",[
						"uses" => "monitorController@cek_br_mhu",
						"alias"=> "monitor.cek_br_mhu"
					]);
	Route::post('/',[
						"uses" => "monitorController@mhuBarging_POST",
						"alias"=> "monitor.mhuBarging_POST"
					]);
			});
//FORM BOAT MHU
Route::get('/mhu/form/boat',[
					"uses" => "mhuController@formBOAT",
					"alias"=> "mhuController.formBOAT"
				]);
Route::post('/mhu/form/boat',[
					"uses" => "mhuController@postBOAT",
					"alias"=> "mhuController.postBOAT"
				]);
Route::get('/mhu/form/boat/q-{dataID}',[
					"uses" => "mhuController@editBOAT",
					"alias"=> "mhuController.editBOAT"
				]);
Route::post('/mhu/form/boat/q-{dataID}',[
					"uses" => "mhuController@updateBOAT",
					"alias"=> "mhuController.updateBOAT"
				]);
Route::get('/mhu/form/boat/delete-{dataID}',[
					"uses" => "mhuController@deleteBOAT",
					"alias"=> "mhuController.deleteBOAT"
				]);
Route::get('/mhu/form/boat/undo-{dataID}',[
					"uses" => "mhuController@undoBOAT",
					"alias"=> "mhuController.undoBOAT"
				]);
//FORM STOCK MHU
Route::get('/mhu/form/stock',[
					"uses" => "mhuController@formSTOCK",
					"alias"=> "mhuController.formSTOCK"
				]);
Route::post('/mhu/form/stock',[
					"uses" => "mhuController@postSTOCK",
					"alias"=> "mhuController.postSTOCK"
				]);
Route::get('/mhu/form/stock/q-{dataID}',[
					"uses" => "mhuController@editSTOCK",
					"alias"=> "mhuController.editSTOCK"
				]);
Route::post('/mhu/form/stock/q-{dataID}',[
					"uses" => "mhuController@updateSTOCK",
					"alias"=> "mhuController.updateSTOCK"
				]);
Route::get('/mhu/form/stock/delete-{dataID}',[
					"uses" => "mhuController@deleteSTOCK",
					"alias"=> "mhuController.deleteSTOCK"
				]);
Route::get('/mhu/form/stock/undo-{dataID}',[
					"uses" => "mhuController@undoSTOCK",
					"alias"=> "mhuController.undoSTOCK"
				]);

//MONITORING MHU

Route::group(['prefix' => '/mr/mhu'], function () {
	Route::get("/hauling",[
						"uses" => "mhuController@hauling",
						"alias"=> "mhuController.hauling"
					]);
	Route::get("/hauling/monthly",[
						"uses" => "mhuController@haulMonthly",
						"alias"=> "mhuController.haulMonthly"
					]);
	Route::get("/hauling/ach",[
						"uses" => "mhuController@haulACH",
						"alias"=> "mhuController.haulACH"
					]);
	Route::get("/crushing",[
						"uses" => "mhuController@crushing",
						"alias"=> "mhuController.crushing"
					]);
	Route::get("/crushing/monthly",[
						"uses" => "mhuController@crushMonthly",
						"alias"=> "mhuController.crushMonthly"
					]);
	Route::get("/crushing/ach",[
						"uses" => "mhuController@crushACH",
						"alias"=> "mhuController.crushACH"
					]);
	Route::get("/barging",[
						"uses" => "mhuController@barging",
						"alias"=> "mhuController.barging"
					]);
	Route::get("/barging/monthly",[
						"uses" => "mhuController@bargeMonthly",
						"alias"=> "mhuController.bargeMonthly"
					]);
	Route::get("/barging/ach",[
						"uses" => "mhuController@bargeACH",
						"alias"=> "mhuController.bargeACH"
					]);

	Route::get("/stock",[
						"uses" => "mhuController@stockProduct",
						"alias"=> "mhuController.stockProduct"
					]);
	Route::get("/boat",[
						"uses" => "mhuController@boat",
						"alias"=> "mhuController.Stock"
					]);
});

Route::get("/cron/job/interval/time",[
					"uses" => "monitorController@intervalTime",
					"alias"=> "monitor.intervalTime"
				]);


// Route::get("/cron/job/interval/time",[
					// "uses" => "monitorController@intervalTime",
					// "alias"=> "monitor.intervalTime"
				// ]);


Route::get("/test/node",[
					"uses" => "sysController@sessionUser",
					"alias"=> "sysController.sessionUser"
				]);
Route::get("/del/session",[
					"uses" => "sysController@sessionDel",
					"alias"=> "sysController.sessionDel"
				]);

Route::get("/test/import",[
					"uses" => "sysController@importForm",
					"alias"=> "sysController.importForm"
				]);
Route::post("/test/import",[
					"uses" => "sysController@importPost",
					"alias"=> "sysController.importPost"
				]);


Route::get("/hotspot/akun",[
	"uses" => "adminController@hotspotData",
	"alias"=> "adminController.hotspotData"
]);


Route::get("/privacy",function(){
	echo "<!DOCTYPE html>
<html>
<head>
	<title>Privacy Abpjobsite</title>
</head>
<body>
<center>
<h2>Kebijakan Dan Privasi</h2>
<p>Aplikasi ini dibuat untuk kebutuhan perusahaan PT. Alamjaya Bara Pratama, dan di kelola langsung oleh IT Developer dari Perusahaan tersebut. tidak untuk komersil tapi sebagai alat bantu dalam pekerjaan.</p>

		<p>Jika ada pertanyaan dan masukan dapat menghubungi ke email : <a href='mailto:admin.it@abpenergy.co.id'>admin.it@abpenergy.co.id</a></p>
		</center>
</body>
</html>


	";
});
Route::get("/contact",function(){
	echo "<!DOCTYPE html>
<html>
<head>
	<title>Privacy Abpjobsite</title>
</head>
<body>
<center>
<p>Aplikasi ini dibuat untuk kebutuhan perusahaan PT. Alamjaya Bara Pratama, dan di kelola langsung oleh IT Developer dari Perusahaan tersebut. tidak untuk komersil tapi sebagai alat bantu dalam pekerjaan.</p>

		<p>Jika ada pertanyaan dan masukan dapat menghubungi ke email :
		<br>
		<br>
		<a href='https://bit.ly/2KPqBOa'><img src='".url('/mail.png')."' width='90px'></a>
		<br>
		<a href='https://bit.ly/2Wf5rhB'><img src='".url('/wa.png')."' width='90px'></a>
		</p>
		</center>
</body>
</html>
	";
});
Route::get("/flutter/get/last/absen",
					[ 'uses'=>'flutter\FlutterController@lastAbsen',
						"alias" => 'flutter.user.lastAbsen'
					]);


Route::get("/mailto",function(){
	return redirect('mailto:admin.it@abpenergy.co.id');
});
Route::get('/cek/server',
                        function(){
													return ["success"=>true];
												});
Route::group(['prefix' => '/pln/form/barging'], function () {
	Route::get('/',[
						"uses" => "monitorController@plnBarging",
						"alias"=> "monitor.plnBarging"
					]);
	Route::get('/q-{dataID}',[
						"uses" => "monitorController@edit_pln_Barging",
						"alias"=> "monitor.edit_pln_Barging"
					]);
	Route::post('/q-{dataID}',[
						"uses" => "monitorController@update_pln_Barging",
						"alias"=> "monitor.update_pln_Barging"
					]);
	Route::get('/delete-{dataID}',[
						"uses" => "monitorController@delete_pln_Barging",
						"alias"=> "monitor.delete_pln_Barging"
					]);
	Route::get('/undo-{dataID}',[
						"uses" => "monitorController@undo_pln_Barging",
						"alias"=> "monitor.undo_pln_Barging"
					]);
	Route::get("/check",[
						"uses" => "monitorController@cek_br_pln",
						"alias"=> "monitor.cek_br_pln"
					]);
	Route::post('/',[
						"uses" => "monitorController@plnBarging_POST",
						"alias"=> "monitor.plnBarging_POST"
					]);
			});
Route::group(['prefix' => '/mr/pln'], function () {
	Route::get("/barging",[
						"uses" => "monSRController@barging",
						"alias"=> "monSRController.barging"
					]);
	Route::get("/barging/monthly",[
						"uses" => "monSRController@bargeMonthly",
						"alias"=> "monSRController.bargeMonthly"
					]);
	Route::get("/barging/ach",[
						"uses" => "monSRController@bargeACH",
						"alias"=> "monSRController.bargeACH"
					]);
	Route::get("/boat",[
						"uses" => "monSRController@boat",
						"alias"=> "monSRController.Stock"
								]);
});

//FORM BOAT MHU
Route::group(['prefix' => 'pln/form/boat'], function () {
Route::get('/',[
					"uses" => "monSRController@formBOAT",
					"alias"=> "monSRController.formBOAT"
				]);
Route::post('/',[
					"uses" => "monSRController@postBOAT",
					"alias"=> "monSRController.postBOAT"
				]);
Route::get('/q-{dataID}',[
					"uses" => "monSRController@editBOAT",
					"alias"=> "monSRController.editBOAT"
				]);
Route::post('/q-{dataID}',[
					"uses" => "monSRController@updateBOAT",
					"alias"=> "monSRController.updateBOAT"
				]);
Route::get('/delete-{dataID}',[
					"uses" => "monSRController@deleteBOAT",
					"alias"=> "monSRController.deleteBOAT"
				]);
Route::get('/undo-{dataID}',[
					"uses" => "monSRController@undoBOAT",
					"alias"=> "monSRController.undoBOAT"
				]);
});
