<?php
//sarana

//karyawan
Route::get('/sarpras/data/karyawan', [ 'uses'=>'sarana\aController@karyawan',
				  "alias" => 'sarpras.karyawan'
				]);
Route::get('/sarpras/data/karyawan/form', [ 'uses'=>'sarana\aController@karyawanForm',
				  "alias" => 'sarpras.karyawanForm'
				]);
Route::post('/sarpras/data/karyawan', [ 'uses'=>'sarana\aController@karyawanPost',
				  "alias" => 'sarpras.karyawanPost'
				]);
Route::get('/sarpras/data/karyawan/edit', [ 'uses'=>'sarana\aController@karyawanEdit',
				  "alias" => 'sarpras.karyawanEdit'
				]);
Route::get('/sarpras/data/karyawan/delete-{data_id}', [ 'uses'=>'sarana\aController@karyawanDel',
				  "alias" => 'sarpras.karyawanDel'
				]);

Route::put('/sarpras/data/karyawan', [ 'uses'=>'sarana\aController@karyawanPUT',
				  "alias" => 'sarpras.karyawanPUT'
				]);

//Driver
Route::get('/sarpras/data/driver', [ 'uses'=>'sarana\aController@driver',
				  "alias" => 'sarpras.driver'
				]);
Route::get('/sarpras/data/driver/form', [ 'uses'=>'sarana\aController@driverForm',
				  "alias" => 'sarpras.driverForm'
				]);
Route::get('/sarpras/data/driver/edit', [ 'uses'=>'sarana\aController@driverEdit',
				  "alias" => 'sarpras.driverEdit'
				]);

Route::post('/sarpras/data/driver', [ 'uses'=>'sarana\aController@driverPOST',
				  "alias" => 'sarpras.driverPOST'
				]);
Route::put('/sarpras/data/driver', [ 'uses'=>'sarana\aController@driverPUT',
				  "alias" => 'sarpras.driverPUT'
				]);
Route::get('/sarpras/data/driver/delete-{data_id}', [ 'uses'=>'sarana\aController@driverDel',
				  "alias" => 'sarpras.driverDel'
				]);

//unit

Route::get('/sarpras/data/sarana', [ 'uses'=>'sarana\aController@unit',
				  "alias" => 'sarpras.unit'
				]);
Route::get('/sarpras/data/sarana/form', [ 'uses'=>'sarana\aController@unitForm',
				  "alias" => 'sarpras.unitForm'
				]);
Route::post('/sarpras/data/sarana', [ 'uses'=>'sarana\aController@unitPost',
				  "alias" => 'sarpras.unitPost'
				]);
Route::get('/sarpras/data/sarana/edit', [ 'uses'=>'sarana\aController@unitEdit',
				  "alias" => 'sarpras.unitEdit'
				]);
Route::put('/sarpras/data/sarana', [ 'uses'=>'sarana\aController@unitPUT',
				  "alias" => 'sarpras.unitPUT'
				]);
Route::get('/sarpras/data/sarana/delete-{data_id}', [ 'uses'=>'sarana\aController@unitDel',
				  "alias" => 'sarpras.unitDel'
				]);
Route::get('/sarpras/data/sarana/restore-{data_id}', [ 'uses'=>'sarana\aController@unitUndo',
				  "alias" => 'sarpras.unitUndo'
				]);

//VENDOR

Route::get('/sarpras/data/vendor', [ 'uses'=>'sarana\aController@vendor',
				  "alias" => 'sarpras.vendor'
				]);
Route::get('/sarpras/data/vendor/form', [ 'uses'=>'sarana\aController@vendorForm',
				  "alias" => 'sarpras.vendorForm'
				]);
Route::post('/sarpras/data/vendor', [ 'uses'=>'sarana\aController@vendorPost',
				  "alias" => 'sarpras.vendorPost'
				]);
Route::get('/sarpras/data/vendor/edit', [ 'uses'=>'sarana\aController@vendorEdit',
				  "alias" => 'sarpras.vendorEdit'
				]);
Route::put('/sarpras/data/vendor', [ 'uses'=>'sarana\aController@vendorPUT',
				  "alias" => 'sarpras.vendorPUT'
				]);
Route::get('/sarpras/data/vendor/delete-{data_id}', [ 'uses'=>'sarana\aController@vendorDel',
				  "alias" => 'sarpras.vendorDel'
				]);
Route::get('/sarpras/data/vendor/restore-{data_id}', [ 'uses'=>'sarana\aController@vendorUndo',
				  "alias" => 'sarpras.vendorUndo'
				]);

//KELUAR MASUK SARANA

Route::get('/sarpras/sarana/keluar-masuk', [ 'uses'=>'sarana\aController@keluar_masuk_sarana',
				  "alias" => 'sarpras.keluar_masuk_sarana'
				]);

Route::get('/sarpras/sarana/form-keluar', [ 'uses'=>'sarana\aController@keluar_sarana',
				  "alias" => 'sarpras.keluar_sarana'
				]);

Route::get('/sarpras/sarana/form-izin-keluar', [ 'uses'=>'sarana\aController@formIzinKeluar',
				  "alias" => 'sarpras.formIzinKeluar'
				]);
Route::post('/sarpras/sarana/form-izin-keluar', [ 'uses'=>'sarana\aController@postIzinKeluar',
				  "alias" => 'sarpras.postIzinKeluar'
				]);

Route::post('/sarpras/unit/check-no-lv', [ 'uses'=>'sarana\aController@checkUnit',
				  "alias" => 'sarpras.checkUnit'
				]);
Route::post('/sarpras/karyawan/check-nik', [ 'uses'=>'sarana\aController@cekKaryawan',
				  "alias" => 'sarpras.cekKaryawan'
				]);
Route::post('/sarpras/sarana/keluar-masuk', [ 'uses'=>'sarana\aController@keluar_masuk_post',
				  "alias" => 'sarpras.keluar_masuk_post'
				]);
Route::post('/sarpras/sarana/approve',[ 'uses'=>'sarana\aController@appr_form',
				  "alias"=>'sarpras.appr.appr_form'
				]);

Route::post('/sarpras/sarana/cancel',[ 'uses'=>'sarana\aController@cancel',
				  "alias"=>'sarpras.appr.cancel'
				]);

Route::put('/sarpras/sarana/keluar-masuk', [ 'uses'=>'sarana\aController@cancel_put',
				  "alias" => 'sarpras.cancel_put'
				]);

Route::get('/sarpras/sarana/keluar-masuk-print-out-{noid_out}', [ 'uses'=>'sarana\aController@printOut',
				  "alias" => 'sarpras.printOut'
				]);

Route::get('/sarpras/report/keluar-masuk', [ 'uses'=>'sarana\aController@ReportK_M',
				  "alias" => 'sarpras.ReportK_M'
				]);

Route::get('/sarpras/report/keluar-masuk/admin', [ 'uses'=>'sarana\aController@ReportK_M_admin',
				  "alias" => 'sarpras.ReportK_M_admin'
				]);

Route::get('/sarpras/report/keluar-masuk/all', [ 'uses'=>'sarana\shController@ReportK_M_admin_kordinator',
				  "alias" => 'sarpras.ReportK_M_admin_kordinator'
				]);
Route::get('/sarpras/sarana/keluar-masuk/admin', [ 'uses'=>'sarana\aController@admin_sarana',
				  "alias" => 'sarpras.admin_sarana'
				]);
Route::get('/sarpras/sarana/keluar-masuk/t_m_in', [ 'uses'=>'sarana\aController@t_m_in',
				  "alias" => 'sarpras.t_m_in'
				]);
Route::post('/sarpras/sarana/keluar-masuk/t_m_in_post', [ 'uses'=>'sarana\aController@t_m_in_post',
				  "alias" => 'sarpras.t_m_in_post'
				]);
Route::get('/sarpras/data/karyawan/create/pwd', [ 'uses'=>'sarana\aController@createPWD',
				  "alias" => 'sarpras.createPWD'
				]);

//edit
Route::get('/sarpras/sarana/keluar-masuk/edit{noid_out}', [ 'uses'=>'sarana\aController@editDoc',
				  "alias" => 'sarpras.editDoc'
				]);
Route::get('/sarpras/sarana/keluar-masuk/Motor/edit{noid_out}', [ 
					'uses'=>'sarana\aController@editMotor',
				  	"alias" => 'sarpras.editMotor'
				]);

Route::post('/sarpras/sarana/keluar-masuk/Motor/edit{noid_out}', [ 
					'uses'=>'sarana\aController@UpdateMotor',
					"alias" => 'sarpras.UpdateMotor'
				]);
Route::post('/sarpras/sarana/keluar-masuk/edit{noid_out}', [ 'uses'=>'sarana\aController@editPost',
				  "alias" => 'sarpras.editPost'
				]);
//cancel

Route::get('/sarpras/sarana/keluar-masuk/cancel{noid_out}', [ 'uses'=>'sarana\aController@cancelDoc',
				  "alias" => 'sarpras.cancelDoc'
				]);
Route::put('/sarpras/sarana/keluar-masuk/cancel{noid_out}', [ 'uses'=>'sarana\aController@cancelPost',
				  "alias" => 'sarpras.cancelPost'
				]);
//section

//kordinator
Route::get('/sarpras/sarana/keluar-masuk-appr/kordinator', [ 'uses'=>'sarana\shController@kordinator_appr',
				  "alias" => 'sarpras.kordinator_appr'
				]);
//section
Route::get('/sarpras/sarana/keluar-masuk-appr/section', [ 'uses'=>'sarana\shController@section_appr',
				  "alias" => 'sarpras.section_appr'
				]);
Route::get('/sarpras/report/keluar-masuk/section', [ 'uses'=>'sarana\shController@ReportK_M_section',
				  "alias" => 'sarpras.ReportK_M_section'
				]);
//kabag hse dept

Route::get('/sarpras/sarana/keluar-masuk-appr/hse', [ 'uses'=>'sarana\kabagController@keluar_masuk_sarana_appr_hse',
				  "alias" => 'sarpras.keluar_masuk_sarana_appr'
				]);
Route::get('/sarpras/report/keluar-masuk/kabag/hse', [ 'uses'=>'sarana\kabagController@ReportK_M_kabag_hse',
				  "alias" => 'sarpras.ReportK_M_kabag'
				]);
//kabag
Route::get('/sarpras/sarana/keluar-masuk-appr', [ 'uses'=>'sarana\kabagController@keluar_masuk_sarana_appr',
				  "alias" => 'sarpras.keluar_masuk_sarana_appr'
				]);
Route::put('/sarpras/sarana/keluar-masuk-appr', [ 'uses'=>'sarana\aController@cancel_put',
				  "alias" => 'sarpras.cancel_put'
				]);
Route::get('/sarpras/report/keluar-masuk/kabag', [ 'uses'=>'sarana\kabagController@ReportK_M_kabag',
				  "alias" => 'sarpras.ReportK_M_kabag'
				]);

//HRD ROUTE
Route::get('/sarpras/sarana/keluar-masuk-appr/hr', [ 'uses'=>'sarana\hrController@hr_appr',
				  "alias" => 'sarpras.hr_appr'
				]);
Route::get('/sarpras/report/keluar-masuk/hr', [ 'uses'=>'sarana\hrController@ReportK_M_hr',
				  "alias" => 'sarpras.ReportK_M_hr'
				]);

Route::get('/sarpras/report/keluar-masuk/kabag/enp', [ 'uses'=>'sarana\kabagController@enp',
				  "alias" => 'sarpras.enp'
				]);
Route::get('/sarpras/android/get/sarana', [ 'uses'=>'api\AndroidController@getListSarana',
				  "alias" => 'sarpras.android.getListSarana'
				]);
Route::get('/sarpras/android/get/karyawan', [ 'uses'=>'api\AndroidController@getListKaryawan',
				  "alias" => 'sarpras.android.getListKaryawan'
				]);


Route::post('/sarpras/android/keluar-masuk/post', 
				[ 'uses'=>'sarana\aController@androidSaranaKeluar',
				  "alias" => 'sarpras.androidSaranaKeluar'
				]);
Route::get('/sarpras/android/keluar-masuk/kabag', 
				[ 'uses'=>'sarana\aController@androidSaranaKeluarKabag',
				  "alias" => 'sarpras.androidSaranaKeluarKabag'
				]);