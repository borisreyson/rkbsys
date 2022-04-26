<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

$masterDomain =  function () {
  Route::middleware('api')->get('/',
  							[ 'uses'=>'flutter\FlutterController@createIDcard',
  								"alias" => 'flutter.create.id.card'
  							]);

  Route::middleware('api')->post('/login',
  						[ 'uses'=>'android\androidController@LoginValidateNew',
  							"alias" => 'abpenergy.login.validate'
  						]);

  Route::group(['prefix' => 'hazard',"middleware"=>"api"], function () {
    Route::get('/user',
                [ 'uses'=>'android\androidController@getListHazard',
                  "alias" => 'abpenergy.getListHazard'
                ]);
    Route::get('/penanggung-jawan',
                [ 'uses'=>'android\androidController@getListHazard',
                  "alias" => 'abpenergy.getListHazard'
                ]);
    Route::get('/safety',
                [ 'uses'=>'hse\inspeksiController@hazardHSE',
                  "alias" => 'inspeksiController.hazardHSE'
                ]);
  });

};
Route::domain('lp.abpjobsite.com')->group($masterDomain);
