<?php
Route::get('/logistic/rkb', [ 'uses'=>'logisticController@rkb',
				  "alias" => 'logistic.rkb'
				]);
Route::get('/mtk/rkb', [ 'uses'=>'logisticController@rkb_mtk',
				  "alias" => 'logistic.rkb.mtk'
				]);
Route::post('/logistic/rkb/detail.py', [ 'uses'=>'logisticController@detail_rkb',
				  "alias" => 'logistic.detail_rkb'
				]);
Route::post('/logistic/close/item', [ 'uses'=>'logisticController@close_item',
				  "alias" => 'logistic.close_item'
				]);
Route::put('/logistic/close/item', [ 'uses'=>'logisticController@item_close',
				  "alias" => 'logistic.item_close'
				]);
Route::post('/logistic/update/item/status', [ 'uses'=>'logisticController@update_status',
				  "alias" => 'logistic.update_status'
				]);
Route::put('/logistic/update/item/status', [ 'uses'=>'logisticController@update_send',
				  "alias" => 'logistic.update_send'
				]);



Route::get('/logistic/tanyakan/{no_rkb}.compose-{user_to}', [ 'uses'=>'logisticController@compose',
				  "alias" => 'logistic.compose'
				]);
Route::get('/logistic/inbox', [ 'uses'=>'logisticController@inbox',
				  "alias" => 'logistic.inbox'
				]);
Route::post('/logistic/inbox', [ 'uses'=>'logisticController@send',
				  "alias" => 'logistic.send'
				]);
Route::get('/logistic/inbox/{id_pesan}.message', [ 'uses'=>'logisticController@message',
				  "alias" => 'logistic.message'
				]);
Route::post('/logistic/inbox/{id_pesan}.message', [ 'uses'=>'logisticController@send1',
				  "alias" => 'logistic.send1'
				]);

//adjust stock
Route::get('/logistic/stock/adjust', [ 'uses'=>'logisticController@adjustStock',
				  "alias" => 'logistic.stock.adjust'
				]);

Route::get('/get/item_data',['uses'=>'logisticController@cariItem',
							'alias'=>'logistic.cari.item']);

