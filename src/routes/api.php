<?php
Route::group(['namespace' => 'Abs\AmcPkg\Api', 'middleware' => ['api', 'auth:api']], function () {
	Route::group(['prefix' => 'api/amc-pkg'], function () {
		//Route::post('punch/status', 'PunchController@status');
	});
});