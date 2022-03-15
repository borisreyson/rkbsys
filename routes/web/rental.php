<?php
//FORM UNIT
Route::get('/mon/unit/rental/form/unit', 'monUnitController@form_unit');
Route::get('/mon/unit/rental/form/unit-{id_unit}', 'monUnitController@edit_unit');
Route::post('/mon/unit/rental/form/unit', 'monUnitController@post_unit');
Route::put('/mon/unit/rental/form/unit-{id_unit}', 'monUnitController@put_unit');

Route::get('/mon/unit/rental/unit-del{id_unit}', 'monUnitController@del_unit');
Route::get('/mon/unit/rental/unit-undo{id_unit}', 'monUnitController@undo_unit');

//FORM HM RENTAL
Route::get('/mon/unit/rental/form/hm', 'monUnitController@form_rental');
Route::post('/mon/unit/rental/form/hm', 'monUnitController@post_rental');
Route::get('/mon/unit/rental/form/hm-{id_hm}', 'monUnitController@edit_hm');
Route::put('/mon/unit/rental/form/hm-{id_hm}', 'monUnitController@put_hm');

Route::get('/mon/unit/rental/hm-del{id_hm}', 'monUnitController@del_hm');
Route::get('/mon/unit/rental/hm-undo{id_hm}', 'monUnitController@undo_hm');

//VIEW

Route::get('/mon/unit/rental/shift-{shift}', 'monUnitController@rentalView');
Route::get('/mon/unit/rental/', 'monUnitController@rentalViewTotal');