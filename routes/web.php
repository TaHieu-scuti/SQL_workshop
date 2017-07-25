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
