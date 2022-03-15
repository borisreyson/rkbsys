<?php

//KTT
Route::get('/absen/ktt/hge', [ 'uses'=>'absen\absenController@absenKTTHGE',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/ktt/hse', [ 'uses'=>'absen\absenController@absenKTTHSE',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/ktt/enp', [ 'uses'=>'absen\absenController@absenKTTENP',
				  "alias" => 'absen.user'
				]);

//KABAG
Route::get('/absen/kabag/hge', [ 'uses'=>'absen\absenController@absenKabagHGE',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/kabag/hse', [ 'uses'=>'absen\absenController@absenKabagHSE',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/kabag/enp', [ 'uses'=>'absen\absenController@absenKabagENP',
				  "alias" => 'absen.user'
				]);

//USERS

Route::get('/absen/user/hge', [ 'uses'=>'absen\absenController@absenUserHGE',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/hse', [ 'uses'=>'absen\absenController@absenUserHSE',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/enp', [ 'uses'=>'absen\absenController@absenUserENP',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/management', [ 'uses'=>'absen\absenController@absenUserMANAGEMENT',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/mtk', [ 'uses'=>'absen\absenController@absenUserMTK',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/error', [ 'uses'=>'absen\absenController@absenError',
				  "alias" => 'absen.error'
				]);

//USER EXPORT
Route::get('/absen/user/hge/export', [ 'uses'=>'absen\absenController@absenUserHGEexport',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/hse/export', [ 'uses'=>'absen\absenController@absenUserHSEexport',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/enp/export', [ 'uses'=>'absen\absenController@absenUserENPexport',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/management/export', [ 'uses'=>'absen\absenController@absenUserMANAGEMENTexport',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/mtk/export', [ 'uses'=>'absen\absenController@absenUserMTKexport',
				  "alias" => 'absen.user'
				]);
Route::get('/absen/user/error/export', [ 'uses'=>'absen\absenController@absenErrorexport',
				  "alias" => 'absen.user.error'
				]);

Route::get('/absen/user/kode/jam/roster', [ 'uses'=>'absen\absenController@kodeJamRoster',
				  "alias" => 'absen.user.kodeJamRoster'
				]);
Route::post('/absen/user/kode/jam/roster', [ 'uses'=>'absen\absenController@kodeJamRosterPost',
				  "alias" => 'absen.user.kodeJamRoster.post'
				]);
Route::put('/absen/user/kode/jam/roster/import', [ 'uses'=>'absen\absenController@kodeJamRosterPut',
				  "alias" => 'absen.user.kodeJamRoster.put'
				]);

Route::get('/absen/roster/karyawan', [ 'uses'=>'absen\absenController@rosterKerja',
				  "alias" => 'absen.user.roster'
				]);

Route::get('/absen/roster/karyawan/lihat', [ 'uses'=>'absen\absenController@rosterKerjaLihat',
				  "alias" => 'absen.user.rosterKerjaLihat'
				]);

Route::post('/absen/roster/karyawan/lihat', [ 'uses'=>'absen\absenController@updateRoster',
				  "alias" => 'absen.user.updateRoster'
				]);

Route::get('/absen/rekap/karyawan', [ 'uses'=>'absen\absenController@rekapAbsen',
				  "alias" => 'absen.user.rekapAbsen'
				]);
Route::get('/absen/rekap/karyawan/export', [ 'uses'=>'absen\absenController@exportRekap',
				  "alias" => 'absen.user.exportRekap'
				]);

Route::get('/absen/new/sub', [ 'uses'=>'absen\absenController@newSub',
				  "alias" => 'absen.user.newSub'
				]);
Route::put('/absen/new/sub', [ 'uses'=>'absen\absenController@newSubPost',
				  "alias" => 'absen.user.newSubPost'
				]);
Route::get('/absen/get/lastAbsen', [ 'uses'=>'absen\absenController@lastAbsen',
				  "alias" => 'absen.user.lastAbsen'
				]);
Route::get('/absen/get/AbsenTigaHari', [ 'uses'=>'absen\absenController@AbsenTigaHari',
				  "alias" => 'absen.user.AbsenTigaHari'
				]);

Route::post('/absen/roster/karyawan', [ 'uses'=>'absen\absenController@postRoster',
				  "alias" => 'absen.user.postRoster'
				]);
Route::get('/absen/user/kode/jam/roster/export', [ 'uses'=>'absen\absenController@exportJamkerja',
				  "alias" => 'absen.user.exportJamkerja'
				]);
Route::post('/absen/roster/karyawan/import', [ 'uses'=>'absen\absenController@importRoster',
				  "alias" => 'absen.user.importRoster'
				]);
Route::post('absen/user/kode/jam/roster/update', [ 'uses'=>'absen\absenController@updateJamKerja',
				"alias" => 'absen.user.updateJamKerja'
			  ]);
Route::post('/absen/rekap/karyawan/validasi', [ 'uses'=>'absen\absenController@validasiAbsen',
				"alias" => 'absen.user.validasiAbsen'
			  ]);
Route::get('/absen/list/all', [ 'uses'=>'absen\absenController@listAllAbsen',
				"alias" => 'absen.user.listAllAbsen'
			  ]);
Route::get('/absen/roster/karyawan/delete', [ 'uses'=>'absen\absenController@deleteRosterUser',
				"alias" => 'absen.user.deleteRosterUser'
			  ]);
Route::get('/absen/presentasi/pengguna', [ 'uses'=>'absen\absenController@persentasiPengguna',
				"alias" => 'absen.user.persentasiPengguna'
			  ]);
Route::get('/absen/form/karyawan', [ 'uses'=>'absen\absenController@formKaryawan',
				"alias" => 'absen.user.formKaryawan'
			  ]);
Route::post('/absen/form/karyawan', [ 'uses'=>'absen\absenController@postKaryawan',
				"alias" => 'absen.user.postKaryawan'
			  ]);
Route::get('/absen/edit/karyawan', [ 'uses'=>'absen\absenController@editKaryawan',
				"alias" => 'absen.user.editKaryawan'
			  ]);
Route::put('/absen/form/karyawan', [ 'uses'=>'absen\absenController@putKaryawan',
				"alias" => 'absen.user.putKaryawan'
			  ]);
Route::get('/absen/apl/masukan', [ 'uses'=>'absen\absenController@aplMasukan',
				"alias" => 'absen.apl.aplMasukan'
			  ]);
Route::get('/absen/map/area', [ 'uses'=>'absen\absenController@mapArea',
				"alias" => 'absen.mapArea'
			  ]);

Route::get('/absen/user/by', [ 'uses'=>'absen\absenController@getAbsensiUser',
				"alias" => 'absen.mapArea'
			  ]);
