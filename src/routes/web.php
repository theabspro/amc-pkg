<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'amc-pkg'], function () {

	
		//Amc Policy
		Route::get('/amc-policy/get-list', 'AmcPolicyController@getAmcPolicyList')->name('getAmcPolicyList');
		Route::get('/amc-policy/get-form-data', 'AmcPolicyController@getAmcPolicyFormData')->name('getAmcPolicyFormData');
		Route::post('/amc-policy/save', 'AmcPolicyController@saveAmcPolicy')->name('saveAmcPolicy');
		Route::get('/amc-policy/delete', 'AmcPolicyController@deleteAmcPolicy')->name('deleteAmcPolicy');
		Route::get('/amc-policy/get-filter-data', 'AmcPolicyController@getAmcPolicyFilterData')->name('getAmcPolicyFilterData');

		

});