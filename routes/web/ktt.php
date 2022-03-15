<?php
Route::get('/ktt/rkb', [ 'uses'=>'kttController@rkb',
				  "alias" => 'ktt.rkb'
				]);

Route::get('/ktt/inbox', [ 'uses'=>'kttController@inbox',
				  "alias" => 'ktt.inbox'
				]);
Route::post('/ktt/inbox', [ 'uses'=>'kttController@send',
				  "alias" => 'ktt.send'
				]);
Route::get('/ktt/inbox/{id_pesan}.message', [ 'uses'=>'kttController@message',
				  "alias" => 'ktt.message'
				]);
Route::post('/ktt/inbox/{id_pesan}.message', [ 'uses'=>'kttController@send1',
				  "alias" => 'ktt.send1'
				]);
Route::get('/sarpras/report/keluar-masuk/ktt', [ 'uses'=>'kttController@kttSarpras',
				  "alias" => 'ktt.kttSarpras'
				]);

