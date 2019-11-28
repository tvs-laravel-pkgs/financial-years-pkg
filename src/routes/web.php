<?php

Route::group(['namespace' => 'Abs\FinancialYearPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'financial-year-pkg'], function () {
	Route::get('/financial-years/get-list', 'FinancialYearController@getFinancialYearList')->name('getFinancialYearList');
	Route::get('/financial-year/get-form-data/{id?}', 'FinancialYearController@getFinancialYearFormData')->name('getFinancialYearFormData');
	Route::post('/financial-year/save', 'FinancialYearController@saveFinancialYear')->name('saveFinancialYear');
	Route::get('/financial-year/delete/{id}', 'FinancialYearController@deleteFinancialYear')->name('deleteFinancialYear');

});