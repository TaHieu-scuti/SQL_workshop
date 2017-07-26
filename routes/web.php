<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    
});
// Route::get('/account_report', 'RepoYssAccountReportController@index')->name('account_report');
Route::get('/account_report', 'RepoYssAccountReport\RepoYssAccountReportController@index');
Route::post('/updateYssAccountReport','RepoYssAccountReport\RepoYssAccountReportController@test');
Route::get('/account_report/export_excel','RepoYssAccountReport\RepoYssAccountReportController@export_Excel')->name('export_excel');
Route::get('/account_report/export_csv','RepoYssAccountReport\RepoYssAccountReportController@export_CSV')->name('export_csv');