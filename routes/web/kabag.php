<?php
Route::get('/kabag/rkb', [ 'uses'=>'kabagController@rkb',
				  "alias" => 'kabag.rkb'
				]);
Route::get('/kabag/tanyakan/{no_rkb}.compose-{user_to}', [ 'uses'=>'kabagController@compose',
				  "alias" => 'kabag.compose'
				]);
Route::get('/kabag/inbox', [ 'uses'=>'kabagController@inbox',
				  "alias" => 'kabag.inbox'
				]);
Route::post('/kabag/inbox', [ 'uses'=>'kabagController@send',
				  "alias" => 'kabag.send'
				]);
Route::get('/kabag/inbox/{id_pesan}.message', [ 'uses'=>'kabagController@message',
				  "alias" => 'kabag.message'
				]);
Route::post('/kabag/inbox/{id_pesan}.message', [ 'uses'=>'kabagController@send1',
				  "alias" => 'kabag.send1'
				]);

