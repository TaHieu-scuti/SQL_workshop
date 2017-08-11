<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    
});
Route::get('/account_report', 'RepoYssAccountReport\RepoYssAccountReportController@index');
Route::post('/account_report', 'RepoYssAccountReport\RepoYssAccountReportController@getDataByFilter');
Route::get('/account_report/export_excel', 'RepoYssAccountReport\RepoYssAccountReportController@exportToExcel')->name('export_excel');
Route::get('/account_report/export_csv', 'RepoYssAccountReport\RepoYssAccountReportController@exportToCsv')->name('export_csv');
