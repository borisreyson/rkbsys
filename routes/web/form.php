<?php
//FORM ROUTE


Route::get('/form_rkb', 'rkbController@rkb_form');
Route::get('/form_rkb/{no_rkb}/delete', 'rkbController@del_rkb_temp');
Route::get('/form_rkb/{no_rkb}', 'rkbController@rkb_rubah');


Route::post('/form_rkb/img-edit.py', 'rkbController@img_replace');
Route::post('/form_rkb/img-upload-{filename}', 'rkbController@img_reupload');
Route::post('/form_rkb/img-reupload-{filename}', 'rkbController@pic_reupload');


Route::get('/v1/form_rkb','v1\rkbController@rkb_form');
Route::post('/v1/rkb','v1\rkbController@rkb_post');
Route::post('/v1/file-upload','v1\rkbController@file_post');


Route::get('/form_dept', 'masterController@form_dept');
Route::get('/form_dept/{iddept}-{dept}.html', 'masterController@get_dept');
Route::get('/form_dept/{iddept}-{dept}.del', 'masterController@del_dept');
//master dept post
Route::post('/form_dept', 'masterController@submit_dept');
Route::post('/form_dept/{iddept}-{dept}.html', 'masterController@update_dept');


Route::get('/sect/form', 'masterController@sect_form');
Route::get('/sect/{iddept}-{idsect}.edit', 'masterController@sect_edit');
Route::get('/sect/{iddept}-{idsect}.del', 'masterController@sect_del');
//master section post
Route::post('/sect/form', 'masterController@sect_create');
Route::post('/sect/{iddept}-{idsect}.edit', 'masterController@sect_update');

//master dept post
Route::post('/form_dept', 'masterController@submit_dept');
Route::post('/form_dept/{iddept}-{dept}.html', 'masterController@update_dept');


Route::get('/form_satuan', 'masterController@form_satuan');
Route::get('/form_satuan/{no}.html', 'masterController@get_satuan');
Route::get('/form_satuan/{no}.del', 'masterController@del_satuan');

//master satuan post
Route::post('/form_satuan', 'masterController@submit_satuan');
Route::post('/form_satuan/{no}.html', 'masterController@up_satuan');


Route::get('/level/form', 'userController@level_form');
Route::post('/level/form', 'userController@level_create');


Route::get('/form_user', 'userController@form_user');
Route::get('/form_user/{username}.html', 'userController@form_edit_user');
Route::get('/form_user/{username}.del', 'userController@user_del');
Route::get('/form_user/{username}.disable', 'userController@user_dis');
Route::get('/form_user/{username}.enable', 'userController@user_en');
Route::get('/form_user/{username}.password', 'userController@form_password_user');
Route::get('/form_user/{username}.plt', 'userController@user_plt');

//master user post
Route::post('/form_user', 'userController@create_user');
Route::post('/form_user/{username}.html', 'userController@update_user');
Route::post('/form_user/{username}.password', 'userController@update_password_user');
//email
Route::get('/form_user/{username}.email', 'userController@email_form');
Route::post('/form_user/{username}.email', 'userController@email_post');
//nik
Route::get('/form_user/{username}.nik', 'userController@nik_form');
Route::post('/form_user/{username}.nik', 'userController@nik_post');
//masteritem
Route::get('/rkb/get/master/item', 'rkbController@masterItem');
Route::get('/rkb/get/master/part_number', 'v1\rkbController@masterPartnumber');

