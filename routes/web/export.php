<?php
Route::get('/export/to/excel', [ 'uses'=>'v2\rkbController@export',
				  "alias" => 'v2.export'
				]);


Route::post('/import/abp/ob', [ 'uses'=>'importController@importAbpOb',
				  "alias" => 'importController.importAbpOb'
				]);
Route::post('/import/abp/hauling', [ 'uses'=>'importController@importAbpHauling',
				  "alias" => 'importController.importAbpHauling'
				]);
Route::post('/import/abp/crushing', [ 'uses'=>'importController@importAbpCrushing',
				  "alias" => 'importController.importAbpCrushing'
				]);
Route::post('/import/abp/barging', [ 'uses'=>'importController@importAbpBarging',
				  "alias" => 'importController.importAbpBarging'
				]);
Route::post('/import/abp/boat', [ 'uses'=>'importController@importAbpboat',
				  "alias" => 'importController.importAbpboat'
				]);
Route::post('/import/abp/sr/expose', [ 'uses'=>'importController@importSrExpose',
				  "alias" => 'importController.importSrExpose'
				]);
Route::get('/import/abp/data/karyawan', [ 'uses'=>'importController@formKaryawanImport',
				  "alias" => 'importController.formKaryawanImport'
				]);
Route::post('/import/abp/data/karyawan', [ 'uses'=>'importController@importDataKaryawan',
				  "alias" => 'importController.importDataKaryawan'
				]);
Route::post('/import/abp/data/karyawan/compare', [ 'uses'=>'importController@compareDataKaryawan',
				  "alias" => 'importController.compareDataKaryawan'
				]);


