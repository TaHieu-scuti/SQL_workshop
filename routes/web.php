<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

Route::get(
    '/login',
    'Auth\LoginController@showLoginForm'
)->name('login');

Route::get(
    '/account_report',
    'RepoYssAccountReport\RepoYssAccountReportController@index'
);

Route::post(
    '/update-table',
    'RepoYssAccountReport\RepoYssAccountReportController@updateTable'
);

Route::get(
    '/account_report/export_excel',
    'RepoYssAccountReport\RepoYssAccountReportController@exportToExcel'
)->name('export_excel');

Route::get(
    '/account_report/export_csv',
    'RepoYssAccountReport\RepoYssAccountReportController@exportToCsv'
)->name('export_csv');

Route::get(
    '/display-graph',
    'RepoYssAccountReport\RepoYssAccountReportController@displayDataOnGraph'
);

Route::post(
    '/display-graph',
    'RepoYssAccountReport\RepoYssAccountReportController@updateGraph'
);

Route::post(
    '/account_report/live_search',
    'RepoYssAccountReport\RepoYssAccountReportController@liveSearch'
);
