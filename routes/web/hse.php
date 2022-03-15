<?php

//HSE
// HAZARD REPORT
Route::get('/hse/admin/hazard/report', [ 'uses'=>'hse\hseController@hazardReport',
				  "alias" => 'hse.hazard.report'
				]);
Route::get('/hse/admin/hazard/report/verifikasi', [ 'uses'=>'hse\hseController@hazardReportVerifikasi',
				  "alias" => 'hse.hazard.report.verifikasi'
				]);
Route::get('/hse/admin/hazard/report/export/all', [ 'uses'=>'hse\hseController@exportHazardReport',
				  "alias" => 'hse.hazard.report.export,all'
				]);

// INSPEKSI

Route::get('/hse/admin/inspeksi/report', [ 'uses'=>'hse\hseController@inspeksiReport',
				  "alias" => 'hse.inspeksi.report'
				]);
Route::get('/hse/admin/inspeksi/report/verifikasi', [ 'uses'=>'hse\hseController@inspeksiReportVerifikasi',
				  "alias" => 'hse.inspeksi.report.verifikasi'
				]);
Route::get('/hse/admin/inspeksi/form', [ 'uses'=>'hse\hseController@inspeksiForm',
				  "alias" => 'hse.inspeksi.form'
				]);
Route::post('/hse/admin/inspeksi/form', [ 'uses'=>'hse\hseController@inspeksiFormPost',
				  "alias" => 'hse.inspeksi.form.post'
				]);
Route::put('/hse/admin/inspeksi/form', [ 'uses'=>'hse\hseController@inspeksiFormPUT',
				  "alias" => 'hse.inspeksi.form.put'
				]);

Route::get('/hse/admin/inspeksi/form/disable', [ 'uses'=>'hse\hseController@inspeksiFormFlag',
				  "alias" => 'hse.inspeksi.form.disable'
				]);
Route::get('/hse/admin/inspeksi/form/enable', [ 'uses'=>'hse\hseController@inspeksiFormFlag',
				  "alias" => 'hse.inspeksi.form.enable'
				]);
Route::get('/hse/admin/inspeksi/form/create', [ 'uses'=>'hse\hseController@inspeksiFormCreate',
				  "alias" => 'hse.inspeksi.form.create'
				]);

Route::post('/hse/admin/inspeksi/form/create', [ 'uses'=>'hse\hseController@inspeksiFormNewPost',
				  "alias" => 'hse.inspeksi.form.create.post'
				]);
Route::put('/hse/admin/inspeksi/form/create', [ 'uses'=>'hse\hseController@inspeksiFormNewPUT',
				  "alias" => 'hse.inspeksi.form.create.put'
				]);

Route::get('/hse/admin/inspeksi/form/create/sub', [ 'uses'=>'hse\hseController@inspeksiFormCreateSub',
				  "alias" => 'hse.inspeksi.form.create.sub'
				]);
Route::post('/hse/admin/inspeksi/form/create/sub', [ 'uses'=>'hse\hseController@inspeksiFormCreateSubPost',
				  "alias" => 'hse.inspeksi.form.create.sub.post'
				]);
Route::put('/hse/admin/inspeksi/form/create/sub', [ 'uses'=>'hse\hseController@inspeksiFormCreateSubPUT',
				  "alias" => 'hse.inspeksi.form.create.sub.PUT'
				]);

Route::get('/hse/android/inspeksi/form', [ 'uses'=>'hse\hseController@androidInspeksiForm',
				  "alias" => 'hse.inspeksi.form.android'
				]);
Route::get('/hse/android/inspeksi/list', [ 'uses'=>'hse\hseController@androidInspeksiList',
				  "alias" => 'hse.inspeksi.list.android'
				]);
Route::get('/hse/android/inspeksi/new', [ 'uses'=>'hse\hseController@androidInspeksiNew',
				  "alias" => 'hse.inspeksi.new.android'
				]);

Route::get('/hse/android/inspeksi/detail', [ 'uses'=>'hse\inspeksiController@inspeksiDetail',
				  "alias" => 'inspeksiController.inspeksiDetail'
				]);
Route::get('/hse/android/inspeksi/new/items', [ 'uses'=>'hse\hseController@androidInspeksiItems',
				  "alias" => 'hse.inspeksi.new.items.android'
				]);

Route::get('/hse/android/inspeksi/new/item/temp', [ 'uses'=>'hse\hseController@androidInspeksiItemTemp',
				  "alias" => 'hse.inspeksi.new.item.temp.android'
				]);
Route::get('/hse/android/inspeksi/new/add/team/temp', [ 'uses'=>'hse\hseController@androidInspeksiAddTeamTemp',
				  "alias" => 'hse.inspeksi.new.add.team.temp.android'
				]);
Route::get('/hse/android/inspeksi/new/list/team/temp', [ 'uses'=>'hse\hseController@androidInspeksiListTeamTemp',
				  "alias" => 'hse.inspeksi.new.list.team.temp.android'
				]);
Route::get('/hse/android/inspeksi/list/team', [ 'uses'=>'hse\inspeksiController@teamInspeksi',
				  "alias" => 'inspeksiController.teamInspeksi'
				]);

Route::get('/hse/android/inspeksi/delete/temp', [ 'uses'=>'hse\hseController@androidInspeksiDeleteTemp',
				  "alias" => 'hse.inspeksi.inspeksi.delete.temp.android'
				]);

Route::post('/hse/android/inspeksi/pica/temp', [ 'uses'=>'hse\hseController@androidInspeksiAddPicaTemp',
				  "alias" => 'hse.inspeksi.inspeksi.pica.temp.android'
				]);
Route::get('/hse/android/inspeksi/pica/temp', [ 'uses'=>'hse\hseController@androidInspeksiPicaTemp',
				  "alias" => 'hse.inspeksi.inspeksi.pica.temp.list.android'
				]);

Route::get('/hse/android/inspeksi/pica/detail', [ 'uses'=>'hse\inspeksiController@androidInspeksiPica',
				  "alias" => 'inspeksiController.androidInspeksiPica'
				]);

Route::post('/hse/android/inspeksi/new/submit', [ 'uses'=>'hse\inspeksiController@createInspeksi',
				  "alias" => 'inspeksiController.createInspeksi'
				]);
Route::get('/hse/android/inspeksi/list/user', [ 'uses'=>'hse\inspeksiController@getInspeksiUser',
				  "alias" => 'inspeksiController.getInspeksiUser'
				]);

Route::get('/hse/admin/inspeksi/report/export/all', [ 'uses'=>'hse\hseController@exportInspeksiReport',
"alias" => 'hse.inspeksi.report.export,all'
]);

// HSE MASTER
// LOKASI
Route::get('/hse/admin/master/lokasi', [ 'uses'=>'hse\hseController@hseMasterLokasi',
				  "alias" => 'hse.master.lokasi'
				]);
Route::get('/hse/admin/master/lokasi/new', [ 'uses'=>'hse\hseController@hseMasterLokasiNew',
				  "alias" => 'hse.master.lokasi.new'
				]);
Route::get('/hse/admin/master/lokasi/ubah', [ 'uses'=>'hse\hseController@hseMasterLokasiNew',
				  "alias" => 'hse.master.lokasi.ubah'
				]);

Route::post('/hse/admin/master/lokasi', [ 'uses'=>'hse\hseController@hseMasterLokasiPost',
				  "alias" => 'hse.master.lokasi.post'
				]);

Route::put('/hse/admin/master/lokasi', [ 'uses'=>'hse\hseController@hseMasterLokasiPUT',
				  "alias" => 'hse.master.lokasi.put'
				]);
// RISK
Route::get('/hse/admin/master/risk', [ 'uses'=>'hse\hseController@hseMasterRisk',
				  "alias" => 'hse.master.risk'
				]);
Route::get('/hse/admin/master/risk/ubah', [ 'uses'=>'hse\hseController@hseMasterRisk',
				  "alias" => 'hse.master.risk.ubah'
				]);

Route::post('/hse/admin/master/risk', [ 'uses'=>'hse\hseController@hseMasterRiskPost',
				  "alias" => 'hse.master.risk.post'
				]);

Route::put('/hse/admin/master/risk', [ 'uses'=>'hse\hseController@hseMasterRiskUbah',
				  "alias" => 'hse.master.risk.ubah'
				]);
// SUMBER BAHAYA
Route::get('/hse/admin/master/sumber/bahaya', [ 'uses'=>'hse\hseController@hseMasterSumberBahaya',
				  "alias" => 'hse.master.sumber.bahaya'
				]);

Route::post('/hse/admin/master/sumber/bahaya', [ 'uses'=>'hse\hseController@hseMasterSumberBahayaPost',
				  "alias" => 'hse.master.sumber.bahaya.post'
				]);

Route::put('/hse/admin/master/sumber/bahaya', [ 'uses'=>'hse\hseController@hseMasterSumberBahayaPUT',
				  "alias" => 'hse.master.sumber.bahaya.put'
				]);

// Matrik Resiko
Route::get('/hse/admin/matrik/hasil', [ 'uses'=>'hse\hseController@hasilMatrikResiko',
				  "alias" => 'hse.master.matrik.resiko.hasil'
				]);
Route::post('/hse/admin/matrik/hasil', [ 'uses'=>'hse\hseController@hasilMatrikResikoPost',
				  "alias" => 'hse.master.matrik.resiko.hasil'
				]);
Route::put('/hse/admin/matrik/hasil', [ 'uses'=>'hse\hseController@hasilMatrikResikoPut',
				  "alias" => 'hse.master.matrik.resiko.hasil'
				]);


Route::get('/hse/admin/matrik/kemungkinan', [ 'uses'=>'hse\hseController@kemungkinanMatrikResiko',
				  "alias" => 'hse.master.matrik.resiko.kemungkinan'
				]);
Route::post('/hse/admin/matrik/kemungkinan', [ 'uses'=>'hse\hseController@kemungkinanMatrikResikoPost',
				  "alias" => 'hse.master.matrik.resiko.kemungkinan'
				]);
Route::put('/hse/admin/matrik/kemungkinan', [ 'uses'=>'hse\hseController@kemungkinanMatrikResikoPut',
				  "alias" => 'hse.master.matrik.resiko.kemungkinan'
				]);

Route::get('/hse/admin/matrik/keparahan', [ 'uses'=>'hse\hseController@keparahanMatrikResiko',
				  "alias" => 'hse.master.matrik.resiko.keparahan'
				]);
Route::post('/hse/admin/matrik/keparahan', [ 'uses'=>'hse\hseController@keparahanMatrikResikoPost',
				  "alias" => 'hse.master.matrik.resiko.keparahan'
				]);
Route::put('/hse/admin/matrik/keparahan', [ 'uses'=>'hse\hseController@keparahanMatrikResikoPut',
				  "alias" => 'hse.master.matrik.resiko.keparahan'
				]);

Route::get('/hse/admin/matrik/table', [ 'uses'=>'hse\hseController@tbMatrikResiko',
				  "alias" => 'hse.master.matrik.resiko.table'
				]);

Route::get('/hse/admin/matrik/table/web/view', [ 'uses'=>'hse\hseController@tbMatrikResikoWebView',
				  "alias" => 'hse.master.matrik.resiko.table/web/view'
				]);
// Matrik Resiko 
Route::get('/hse/admin/resiko/kemungkinan', 'hse\hseController@resikoKemungkinan');
Route::get('/hse/admin/resiko/keparahan', 'hse\hseController@resikoKeparahan');
Route::get('/hse/admin/resiko/keparahan/full', 'hse\hseController@resikoKeparahanFull');
Route::get('/hse/admin/resiko/keparahan/detail', 'hse\hseController@resikoKeparahanDet');
// Matrik Resiko 
// Hirarki Pengendalian
Route::get('/hse/admin/hiraiki/pengendalian', 'hse\hseController@hirarkiPengendalian');
Route::get('/hse/admin/hiraiki/pengendalian/detail', 'hse\hseController@detailPengendalian');
// Hirarki Pengendalian

// Ganti Sandi
Route::post('/hse/android/ganti/sandi', 'hse\inspeksiController@gantiSandi');
// Ganti Sandi
// loadProfile
Route::get('/hse/android/load/data/profile', 'hse\inspeksiController@loadProfile');
Route::post('/hse/android/load/data/profile', 'hse\inspeksiController@simpanProfile');
// loadProfile
// saveCompany
Route::post('/hse/android/save/data/company', 'hse\inspeksiController@saveCompany');
Route::put('/hse/android/save/data/company', 'hse\inspeksiController@updateCompany');
// saveCompany
Route::get('/android/api/hse/list/hazard/report/saya', 'hse\inspeksiController@hazardKeSaya');
Route::get('/android/api/hse/list/hazard/report/hse', 'hse\inspeksiController@hazardHSE');
// FOTO PROFILE
Route::post('/android/api/user/foto/profile', 'hse\inspeksiController@fotoProfile');
Route::get('/android/api/list/users', 'hse\inspeksiController@listUser');
Route::get('/android/api/list/users/all', 'hse\inspeksiController@listUserAll');
Route::get('/android/api/hse/hazard/verify', 'hse\inspeksiController@hazardReportVerifikasi');

// Inspeksi Android
Route::get('/android/api/hse/inspeksi/all', [ 'uses'=>'hse\inspeksiController@inspeksiAll',
"alias" => 'hse.inspeksi.android.all'
]);
Route::get('/android/api/hse/inspeksi/user', [ 'uses'=>'hse\inspeksiController@inspeksiUser',
				  "alias" => 'hse.inspeksi.android.inspeksiUser'
				]);
Route::get('/android/api/hse/hazard/delete', [ 'uses'=>'hse\inspeksiController@deleteHazard',
				  "alias" => 'hse.inspeksi.android.deleteHazard'
				]);

Route::get('/android/api/request/password/reset', [ 'uses'=>'hse\inspeksiController@resetPassword',
				  "alias" => 'hse.inspeksi.android.resetPassword'
				]);
Route::get('/android/api/request/password/reset/create', [ 'uses'=>'hse\inspeksiController@createTokenReset',
				  "alias" => 'hse.inspeksi.android.createTokenReset'
				]);
Route::get('/android/api/request/password/reset/validate', [ 'uses'=>'hse\inspeksiController@updatePassword',
				  "alias" => 'hse.inspeksi.android.updatePassword'
				]);

Route::post('/android/api/request/password/reset/validate', [ 'uses'=>'hse\inspeksiController@newPassword',
				  "alias" => 'hse.inspeksi.android.newPassword'
				]);

Route::get('/android/api/request/password/reset/view', [ 'uses'=>'hse\inspeksiController@mailResetView',
				  "alias" => 'hse.inspeksi.android.mailResetView'
				]);
Route::get('/android/api/reset/all/password', [ 'uses'=>'hse\inspeksiController@resetAllPassword',
				  "alias" => 'hse.inspeksi.android.resetAllPassword'
				]);
Route::post('/android/api/reset/all/password', [ 'uses'=>'hse\inspeksiController@newPassword',
				  "alias" => 'hse.inspeksi.android.resetAllPassword'
				]);