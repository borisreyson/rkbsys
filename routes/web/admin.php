<?php
Route::get('/admin/rkb', [ 'uses'=>'adminController@rkb',
				  "alias" => 'admin.rkb'
				]);
Route::get('/admin/ttd/{f_name}', [ 'uses'=>'adminController@ttd',
				  "alias" => 'admin.ttd'
				]);
Route::get('/admin/rkb/blade', [ 'uses'=>'adminController@rkbAdmin',
				  "alias" => 'admin.blade'
				]);

Route::get('/admin/printRkb', [ 'uses'=>'adminController@rkbPrint',
				  "alias" => 'admin.rkbPrint'
				]);


Route::get('/admin/inbox', [ 'uses'=>'adminController@inbox',
				  "alias" => 'admin.inbox'
				]);
Route::post('/admin/inbox', [ 'uses'=>'adminController@send',
				  "alias" => 'admin.send'
				]);
Route::get('/admin/inbox/{id_pesan}.message', [ 'uses'=>'adminController@message',
				  "alias" => 'admin.message'
				]);
Route::post('/admin/inbox/{id_pesan}.message', [ 'uses'=>'adminController@send1',
				  "alias" => 'admin.send1'
				]);

//inventory
//category
Route::get('/admin/inventory/category', [ 'uses'=>'inventory\invtController@invt_category',
				  "alias" => 'admin.invt_category'
				]);

Route::get('/admin/inventory/category/new', [ 'uses'=>'inventory\invtController@categoryNew',
				  "alias" => 'admin.categoryNew'
				]);
Route::get('/admin/inventory/category/edit', [ 'uses'=>'inventory\invtController@editCat',
				  "alias" => 'admin.editCat'
				]);
Route::post('/admin/inventory/category', [ 'uses'=>'inventory\invtController@postNewCat',
				  "alias" => 'admin.postNewCat'
				]);
Route::put('/admin/inventory/category', [ 'uses'=>'inventory\invtController@putCat',
				  "alias" => 'admin.putCat'
				]);
Route::get('/admin/inventory/category/status-{idCat}-{status}', [ 'uses'=>'inventory\invtController@statCat',
				  "alias" => 'admin.statCat'
				]);
Route::get('/admin/inventory/category/del-{idCat}', [ 'uses'=>'inventory\invtController@delCat',
				  "alias" => 'admin.delCat'
				]);



//condition
Route::get('/admin/inventory/condition', [ 'uses'=>'inventory\invtController@condition',
				  "alias" => 'admin.condition'
				]);
Route::get('/admin/inventory/condition/new', [ 'uses'=>'inventory\invtController@conditionNew',
				  "alias" => 'admin.conditionNew'
				]);
Route::post('/admin/inventory/condition', [ 'uses'=>'inventory\invtController@conditionPost',
				  "alias" => 'admin.conditionPost'
				]);
Route::get('/admin/inventory/condition/edit', [ 'uses'=>'inventory\invtController@editCond',
				  "alias" => 'admin.editCond'
				]);
Route::put('/admin/inventory/condition', [ 'uses'=>'inventory\invtController@putcond',
				  "alias" => 'admin.putcond'
				]);
Route::get('/admin/inventory/condition/status-{idCon}-{status}', [ 'uses'=>'inventory\invtController@statCond',
				  "alias" => 'admin.statCond'
				]);
Route::get('/admin/inventory/condition/del-{idCon}', [ 'uses'=>'inventory\invtController@delCond',
				  "alias" => 'admin.delCond'
				]);


//location
Route::get('/admin/inventory/location', [ 'uses'=>'inventory\invtController@location',
				  "alias" => 'admin.location'
				]);
Route::get('/admin/inventory/location/new', [ 'uses'=>'inventory\invtController@locationNew',
				  "alias" => 'admin.locationNew'
				]);
Route::post('/admin/inventory/location', [ 'uses'=>'inventory\invtController@locationPost',
				  "alias" => 'admin.locationPost'
				]);
Route::get('/admin/inventory/location/edit', [ 'uses'=>'inventory\invtController@editLoc',
				  "alias" => 'admin.editLoc'
				]);
Route::put('/admin/inventory/location', [ 'uses'=>'inventory\invtController@putLoc',
				  "alias" => 'admin.putLoc'
				]);
Route::get('/admin/inventory/location/status-{idLoc}-{status}', [ 'uses'=>'inventory\invtController@statLoc',
				  "alias" => 'admin.statLoc'
				]);
Route::get('/admin/inventory/location/del-{idLoc}', [ 'uses'=>'inventory\invtController@delLoc',
				  "alias" => 'admin.delLoc'
				]);

//method
Route::get('/admin/inventory/method', [ 'uses'=>'inventory\invtController@method',
				  "alias" => 'admin.method'
				]);
Route::get('/admin/inventory/method/new', [ 'uses'=>'inventory\invtController@methodNew',
				  "alias" => 'admin.methodNew'
				]);
Route::post('/admin/inventory/method', [ 'uses'=>'inventory\invtController@methodPost',
				  "alias" => 'admin.methodPost'
				]);
Route::get('/admin/inventory/method/edit', [ 'uses'=>'inventory\invtController@editMethod',
				  "alias" => 'admin.editMethod'
				]);
Route::put('/admin/inventory/method', [ 'uses'=>'inventory\invtController@putMethod',
				  "alias" => 'admin.putMethod'
				]);
Route::get('/admin/inventory/method/status-{idMethod}-{status}', [ 'uses'=>'inventory\invtController@statMethod',
				  "alias" => 'admin.statMethod'
				]);
Route::get('/admin/inventory/method/del-{idMethod}', [ 'uses'=>'inventory\invtController@delMethod',
				  "alias" => 'admin.AllInbox'
				]);

Route::get('admin/all/inbox', [ 'uses'=>'adminController@AllInbox',
				  "alias" => 'admin.delMethod'
				]);
Route::get('admin/all/inbox/{id_pesan}.message', [ 'uses'=>'adminController@AllMessage',
				  "alias" => 'admin.ReadAllInbox'
				]);

Route::get('/kabag/alldept/rkb',['uses'=>'adminController@AllRKB','alias'=>'admin.all.rkbs']);


Route::get('/kabag/mtk/rkb',['uses'=>'adminController@mtkRKB','alias'=>'admin.mtk.rkbs']);



Route::get('/test/datajson',['uses'=>'adminController@dataJson','alias'=>'admin.data.Json']);


Route::get('/rule/user',['uses'=>'adminController@ruleUser','alias'=>'admin.rule.ruleUser']);

Route::post('/rule/user/edit',['uses'=>'adminController@ruleUserEdit','alias'=>'admin.rule.ruleUserEdit']);

Route::post('/rule/user',['uses'=>'adminController@ruleUpdate','alias'=>'admin.rule.ruleUpdate']);

Route::get('/data/karyawan/admin',['uses'=>'adminController@dataKaryawan','alias'=>'admin.data.karyawan']);
Route::post('/data/karyawan',['uses'=>'adminController@dataKaryawanKirim','alias'=>'admin.data.karyawan.kirim']);
Route::get('/karyawan/createpassword',['uses'=>'adminController@karyawanCPASS','alias'=>'admin.karyawan.password']);

//users

Route::get('/manage/users',['uses'=>'adminController@users','alias'=>'admin.users']);
Route::get('/data/karyawan/admin/disable',['uses'=>'adminController@dataKaryawanDisable','alias'=>'admin.dataKaryawanDisable']);
Route::get('/data/karyawan/admin/enable',['uses'=>'adminController@dataKaryawanEnable','alias'=>'admin.dataKaryawanEnable']);


Route::get('/manage/users/json',['uses'=>'adminController@users_json','alias'=>'admin.users.json']);

Route::get('/neardeal/get/json',['uses'=>'adminController@neardealJson','alias'=>'admin.neardealJson']);
Route::post('/neardeal/get/json',['uses'=>'adminController@neardealPost','alias'=>'admin.neardealPost']);
Route::get('/tulis/pesan',[
	'uses'=>'api\FirebaseController@tulisPesan',
	'alias'=>'admin.tulisPesan'
]);
Route::post('/tulis/pesan',[
	'uses'=>'api\FirebaseController@simpanPesan',
	'alias'=>'admin.simpanPesan'
]);


Route::get('sql_backup',[
	'uses'=>'adminController@sql_backup',
	'alias'=>'admin.sql_backup'
]);

Route::get('/admin/google/drive', function() {
    Storage::disk('google')->put('test.txt', 'Hello World');
});
Route::get('/admin/google/drive/delete', function() {
     $filename = 'test.txt';
    $contents = collect(Storage::disk('google')->listContents());
    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        ->first(); // there can be duplicate file names!

    Storage::disk('google')->delete($file['path']);

    return 'File was deleted from Google Drive';
});

Route::get('/admin/google/drive/get', function() {
     $filename = 'test.txt';
    $contents = collect(Storage::disk('google')->listContents());
    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        ->first(); // there can be duplicate file names!

    $rawData =Storage::disk('google')->get($file['path']);

    return response($rawData, 200)
        ->header('ContentType', $file['mimetype'])
        ->header('Content-Disposition', "attachment; filename='$filename'");
});
Route::get('/test',[
	'uses'=>'adminController@test',
	'alias'=>'admin.test'
]);

Route::get('/test/hazard',[
	'uses'=>'monitorController@hazardReportTenggat',
	'alias'=>'admin.hazardReportTenggat'
]);
