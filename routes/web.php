<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

Route::get(
    '/',
    function () {
        return redirect('login');
    }
);

Route::get(
    '/login',
    'Auth\LoginController@showLoginForm'
)->name('login');

Route::post(
    '/login',
    'Auth\LoginController@login'
);

Route::get(
    '/logout',
    'Auth\LoginController@logout'
);

Route::get(
    '/home',
    function () {
        return redirect('account_report');
    }
);

Route::get(
    '/account_report',
    'RepoYssAccountReport\RepoYssAccountReportController@index'
)->name('account_report');

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
    'RepoYssAccountReport\RepoYssAccountReportController@displayGraph'
);

Route::post(
    '/display-graph',
    'RepoYssAccountReport\RepoYssAccountReportController@displayGraph'
);

Route::post(
    '/account_report/live_search',
    'RepoYssAccountReport\RepoYssAccountReportController@liveSearch'
);
