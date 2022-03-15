<?php
//MASTER ITEM
Route::get('/inventory/view/stock', [ 'uses'=>'inventory\StockController@viewStock',
				  "alias" => 'inventory.viewStock'
				]);
Route::get('/monitoring/sr/daily', [ 'uses'=>'monSRController@dailySR',
				  "alias" => 'monSRController.dailySR'
				]);
Route::get('/monitoring/sr/expose', [ 'uses'=>'monSRController@exposeForm',
				  "alias" => 'monSRController.exposeForm'
				]);
Route::get('/monitoring/sr/expose/edit-{id}', [ 'uses'=>'monSRController@exposeFormEdit',
				  "alias" => 'monSRController.exposeFormEdit'
				]);
Route::get('/monitoring/sr/expose/delete-{id}', [ 'uses'=>'monSRController@exposeDelete',
				  "alias" => 'monSRController.exposeDelete'
				]);
Route::get('/monitoring/sr/expose/undo-{id}', [ 'uses'=>'monSRController@exposeUndo',
				  "alias" => 'monSRController.exposeUndo'
				]);

Route::post('/monitoring/sr/expose', [ 'uses'=>'monSRController@exposePost',
				  "alias" => 'monSRController.exposePost'
				]);
Route::post('/monitoring/sr/expose/edit-{id}', [ 'uses'=>'monSRController@exposeUpdate',
				  "alias" => 'monSRController.exposeUpdate'
				]);