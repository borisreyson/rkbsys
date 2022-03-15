<?php
//MASTER ITEM
Route::get('/inventory/master', [ 'uses'=>'inventory\invtController@master',
				  "alias" => 'inventory.master'
				]);
Route::get('/inventory/master/new', [ 'uses'=>'inventory\invtController@masterNew',
				  "alias" => 'inventory.masterNew'
				]);
Route::post('/inventory/master', [ 'uses'=>'inventory\invtController@masterPost',
				  "alias" => 'inventory.masterPost'
				]);
Route::get('/inventory/master/edit', [ 'uses'=>'inventory\invtController@editMaster',
				  "alias" => 'inventory.editMaster'
				]);
Route::put('/inventory/master', [ 'uses'=>'inventory\invtController@putMaster',
				  "alias" => 'inventory.putMaster'
				]);
Route::get('/inventory/master/status-{idMethod}-{status}', [ 'uses'=>'inventory\invtController@statMaster',
				  "alias" => 'inventory.statMaster'
				]);
Route::get('/inventory/master/del-{idMethod}', [ 'uses'=>'inventory\invtController@delMaster',
				  "alias" => 'inventory.delMaster'
				]);

//SUPLIER
Route::get('/inventory/suplier', [ 'uses'=>'inventory\invtController@suplier',
				  "alias" => 'inventory.suplier'
				]);
Route::get('/inventory/suplier/new', [ 'uses'=>'inventory\invtController@suplierNew',
				  "alias" => 'inventory.suplierNew'
				]);
Route::post('/inventory/suplier', [ 'uses'=>'inventory\invtController@suplierPost',
				  "alias" => 'inventory.suplierPost'
				]);
Route::get('/inventory/suplier/edit', [ 'uses'=>'inventory\invtController@editSuplier',
				  "alias" => 'inventory.editSuplier'
				]);
Route::put('/inventory/suplier', [ 'uses'=>'inventory\invtController@putSuplier',
				  "alias" => 'inventory.putSuplier'
				]);
Route::get('/inventory/suplier/status-{suplier}-{status}', [ 'uses'=>'inventory\invtController@statSuplier',
				  "alias" => 'inventory.statSuplier'
				]);
Route::get('/inventory/suplier/del-{suplier}', [ 'uses'=>'inventory\invtController@delSuplier',
				  "alias" => 'inventory.delSuplier'
				]);

//STOCK
Route::get('/inventory/stock', [ 'uses'=>'inventory\invtController@stock',
				  "alias" => 'inventory.stock'
				]);
Route::get('/inventory/stock/update/status', [ 'uses'=>'inventory\invtController@stockUpdateStatus',
				  "alias" => 'inventory.stockUpdateStatus'
				]);
Route::get('/inventory/stock/update/rkb', [ 'uses'=>'inventory\invtController@updateRkb',
				  "alias" => 'inventory.updateRkb'
				]);
Route::get('/inventory/stock/user', [ 'uses'=>'inventory\invtController@stockUser',
				  "alias" => 'inventory.stockUser'
				]);


//STOCK
Route::post('/inventory/stock', [ 'uses'=>'inventory\invtController@stockPost',
				  "alias" => 'inventory.stockPost'
				]);

Route::get('/inventory/stock/new', [ 'uses'=>'inventory\invtController@stockIn',
				  "alias" => 'inventory.stockIn'
				]);



Route::post('/inventory/stock/fade', [ 'uses'=>'inventory\invtController@stockFade',
				  "alias" => 'inventory.stockFade'
				]);

Route::get('/inventory/get/master/item', [ 'uses'=>'sysController@dataItem',
				  "alias" => 'inventory.dataItem'
				]);
Route::get('/inventory/get/master/dataStockItem', [ 'uses'=>'sysController@dataStockItem',
				  "alias" => 'inventory.dataStockItem'
				]);

Route::get('/inventory/get/master/location', [ 'uses'=>'sysController@dataLocation',
				  "alias" => 'inventory.dataLocation'
				]);
Route::get('/inventory/get/master/suplier', [ 'uses'=>'sysController@dataSuplier',
				  "alias" => 'inventory.dataSuplier'
				]);
Route::get('/inventory/get/master/category', [ 'uses'=>'sysController@dataCategory',
				  "alias" => 'inventory.dataCategory'
				]);
Route::get('/inventory/get/master/condition', [ 'uses'=>'sysController@dataCondition',
				  "alias" => 'inventory.dataCondition'
				]);
Route::get('/inventory/stock-{item}.in', [ 'uses'=>'inventory\invtController@detailStock',
				  "alias" => 'inventory.detailStock'
				]);
Route::get('/inventory/stock-{item}.out', [ 'uses'=>'inventory\invtController@detailStockOut',
				  "alias" => 'inventory.detailStock'
				]);

Route::get('/inventory/stock/out/new', [ 'uses'=>'inventory\invtController@stockOut',
				  "alias" => 'inventory.stockOut'
				]);
Route::post('/get/rkb/details.complate', [ 'uses'=>'sysController@GetRKBDetail',
				  "alias" => 'inventory.GetRKBDetail'
				]);
Route::get('/get/rkb/details.popup', [ 'uses'=>'sysController@GetRKBDetailPopUp',
				  "alias" => 'inventory.GetRKBDetailPopUp'
				]);
Route::get('/get/rkb/details.popup-html', [ 'uses'=>'sysController@GetRKBDetailPopUpAll',
				  "alias" => 'inventory.GetRKBDetailPopUpAll'
				]);



Route::put('/inventory/stock', [ 'uses'=>'inventory\invtController@stockPostOut',
				  "alias" => 'inventory.stockPostOut'
				]);


//REPORT INVETORY
Route::get('/inventory/report/stock', [ 'uses'=>'inventory\invtController@reportStock',
				  "alias" => 'inventory.reportStock'
				]);
Route::get('/inventory/report/stock-in', [ 'uses'=>'inventory\invtController@reportStockIn',
				  "alias" => 'inventory.reportStockIn'
				]);
Route::get('/inventory/report/stock-out', [ 'uses'=>'inventory\invtController@reportStockOut',
				  "alias" => 'inventory.reportStockOut'
				]);

Route::get('/inventory/report/stock-{item}-in', [ 'uses'=>'inventory\invtController@reportItemIn',
				  "alias" => 'inventory.reportItemIn'
				]);
Route::get('/inventory/report/stock-{item}-out', [ 'uses'=>'inventory\invtController@reportItemOut',
				  "alias" => 'inventory.reportItemOut'
				]);

//check stock out

Route::get('/check/stock/out',[
					"uses" => "inventory\invtController@checkStockOut",
					"alias"=> "inventory.checkStockOut"
				]);

Route::get('/check/stock/out-{noid_out}',[
					"uses" => "inventory\invtController@printStockOut",
					"alias"=> "inventory.printStockOut"
				]);

Route::get('/admin/inventory/category/new/vendor',[
					"uses" => "inventory\invtController@categoryVendor",
					"alias"=> "inventory.categoryVendor"
				]);

Route::post('/admin/inventory/category/new/vendor',[
					"uses" => "inventory\invtController@categoryVendorPost",
					"alias"=> "inventory.categoryVendorPost"
				]);
Route::put('/admin/inventory/category/new/vendor',[
					"uses" => "inventory\invtController@categoryVendorPut",
					"alias"=> "inventory.categoryVendorPut"
				]);


Route::get('/admin/inventory/category/vendor/del-{kode}',[
					"uses" => "inventory\invtController@categoryVendorDel",
					"alias"=> "inventory.categoryVendorDel"
				]);
Route::get('/admin/inventory/category/edit/vendor',[
					"uses" => "inventory\invtController@categoryVendorEdt",
					"alias"=> "inventory.categoryVendorEdt"
				]);



Route::get('/admin/inventory/category/new/item',[
					"uses" => "inventory\invtController@categoryItemNew",
					"alias"=> "inventory.categoryItemNew"
				]);
Route::post('/admin/inventory/category/new/item',[
					"uses" => "inventory\invtController@categoryItemPost",
					"alias"=> "inventory.categoryItemPost"
				]);
Route::get('/admin/inventory/category/edit/item',[
					"uses" => "inventory\invtController@categoryItemEdt",
					"alias"=> "inventory.categoryItemEdt"
				]);
Route::get('/admin/inventory/category/item/del-{item}',[
					"uses" => "inventory\invtController@categoryItemDel",
					"alias"=> "inventory.categoryItemDel"
				]);

Route::get('/inventory/modal/item/detail',[
					"uses" => "inventory\invtController@detailItem",
					"alias"=> "inventory.detailItem"
				]);


Route::get('/inventory/popup/detRKB',[
					"uses" => "inventory\invtController@detPOP",
					"alias"=> "inventory.detPOP"
				]);


Route::get('/inventory/stok/masuk',[
					"uses" => "inventory\invtController@stokmasuk",
					"alias"=> "inventory.stokmasuk"
				]);

Route::post('/inventory/view/QRcode',[
					"uses" => "inventory\invtController@itemQRcode",
					"alias"=> "inventory.itemQRcode"
				]);
Route::get('/inventory/view/QRcode',[
					"uses" => "inventory\invtController@itemQRcode",
					"alias"=> "inventory.itemQRcode"
				]);
Route::get('/inventory/stok/masuk/list-{eq}',[
					"uses" => "inventory\invtController@listItem",
					"alias"=> "inventory.listItem"
				]);
Route::get('/inventory/master/item/list-{eq}',[
					"uses" => "inventory\invtController@listMasterItem",
					"alias"=> "inventory.listMasterItem"
				]);

Route::get('/inventory/master/kondisi/list-{eq}',[
					"uses" => "inventory\invtController@listKondisi",
					"alias"=> "inventory.listKondisi"
				]);

Route::get('/inventory/master/vendor/list-{eq}',[
					"uses" => "inventory\invtController@listVendor",
					"alias"=> "inventory.listVendor"
				]);

Route::get('/inventory/master/lokasi/list-{eq}',[
					"uses" => "inventory\invtController@listLokasi",
					"alias"=> "inventory.listLokasi"
				]);

Route::post('/inventory/stok/store',[
					"uses" => "inventory\invtController@storeStok",
					"alias"=> "inventory.storeStok"
				]);
Route::get('/inventory/stockAll.in',[
					"uses" => "inventory\invtController@stokAllIn",
					"alias"=> "inventory.stokAllIn"
				]);

Route::get('/inventory/stockAll.out',[
					"uses" => "inventory\invtController@stokAllOut",
					"alias"=> "inventory.stokAllOut"
				]);
Route::get('/inventory/update/data',[
					"uses" => "inventory\invtController@UpdateData",
					"alias"=> "inventory.UpdateData"
				]);
Route::get('/inventory/user/stock',[
					"uses" => "inventory\invtController@userSTOCK",
					"alias"=> "inventory.userSTOCK"
				]);
Route::get('/inventory/cek/master',[
					"uses" => "inventory\invtController@cekMasterItem",
					"alias"=> "inventory.cekMasterItem"
				]);
Route::get('/inventory/compare/quantity',[
					"uses" => "inventory\invtController@compare",
					"alias"=> "inventory.compare"
				]);
Route::get('stock.in',[
					"uses" => "inventory\invtController@cekStokUser",
					"alias"=> "inventory.cekStokUser"
				]);
Route::get('/masteritem/request',[
					"uses" => "inventory\invtController@masteritemRequest",
					"alias"=> "inventory.masteritemRequest"
				]);
Route::post('/masteritem/request',[
					"uses" => "inventory\invtController@masteritemRequestPOST",
					"alias"=> "inventory.masteritemRequestPOST"
				]);

Route::get('/masteritem/request/detail',[
					"uses" => "inventory\invtController@masteritemRequestDetail",
					"alias"=> "inventory.masteritemRequestDetail"
				]);

Route::get('/masteritem/request/detail/log',[
					"uses" => "inventory\invtController@masteritemRequestDetailLog",
					"alias"=> "inventory.masteritemRequestDetailLog"
				]);
Route::get('/masteritem/request/item',[
					"uses" => "inventory\invtController@editRequestItem",
					"alias"=> "inventory.editRequestItem"
				]);

Route::post('/masteritem/request/detail',[
					"uses" => "inventory\invtController@RequestItemPUT",
					"alias"=> "inventory.RequestItemPUT"
				]);


Route::get('/masteritem/request/create',[
					"uses" => "inventory\invtController@RequestCreate",
					"alias"=> "inventory.RequestCreate"
				]);
Route::put('/masteritem/request/detail',[
					"uses" => "inventory\invtController@RequestPUT",
					"alias"=> "inventory.RequestPUT"
				]);
Route::get('/inventory/report/stokin-get',[
					"uses" => "inventory\invtController@reportStokIn",
					"alias"=> "inventory.reportStokIn"
				]);
Route::get('/inventory/report/stok-now',[
					"uses" => "inventory\invtController@reportStokAll",
					"alias"=> "inventory.reportStokAll"
				]);

Route::get('/inventory/report/stokout-get',[
					"uses" => "inventory\invtController@reportStokOutAll",
					"alias"=> "inventory.reportStokAll"
				]);
Route::get('/inventory/report/stokformat-get',[
					"uses" => "inventory\invtController@exportFormat",
					"alias"=> "inventory.exportFormat"
				]);
Route::get('/data/karyawan',[
					"uses" => "inventory\invtController@datakaryawan",
					"alias"=> "data.karyawan"
				]);

Route::get('/export/master/item',[
					"uses" => "inventory\StockController@getMasterItem",
					"alias"=> "data.getMasterItem"
				]);
Route::get('/inv/check/item/has/insert',[
					"uses" => "inventory\StockController@chkItemOnsite",
					"alias"=> "data.chkItemOnsite"
				]);

