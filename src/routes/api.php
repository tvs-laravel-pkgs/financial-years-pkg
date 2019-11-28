<?php
Route::group(['namespace' => 'Abs\FinancialYearPkg\Api', 'middleware' => ['api']], function () {
	Route::group(['prefix' => 'financial-year-pkg/api'], function () {
		Route::group(['middleware' => ['auth:api']], function () {
			// Route::get('taxes/get', 'TaxController@getTaxes');
		});
	});
});