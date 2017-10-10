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

Route::prefix('account_report')->group(function () {
    Route::get(
        '/',
        'RepoYssAccountReport\RepoYssAccountReportController@index'
    )->name('account_report');
    Route::post(
        '/update-table',
        'RepoYssAccountReport\RepoYssAccountReportController@updateTable'
    );
    Route::get(
        '/display-graph',
        'RepoYssAccountReport\RepoYssAccountReportController@displayGraph'
    );
    Route::post(
        '/display-graph',
        'RepoYssAccountReport\RepoYssAccountReportController@displayGraph'
    );
    Route::post(
        '/live_search',
        'RepoYssAccountReport\RepoYssAccountReportController@liveSearch'
    );
    Route::get(
        '/export_excel',
        'RepoYssAccountReport\RepoYssAccountReportController@exportToExcel'
    );

    Route::get(
        '/export_csv',
        'RepoYssAccountReport\RepoYssAccountReportController@exportToCsv'
    );
});

Route::prefix('campaign-report')->group(function () {
    Route::get(
        '/',
        'RepoYssCampaignReport\RepoYssCampaignReportController@index'
    );
    Route::get(
        '/display-graph',
        'RepoYssCampaignReport\RepoYssCampaignReportController@displayGraph'
    );
    Route::post(
        '/display-graph',
        'RepoYssCampaignReport\RepoYssCampaignReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssCampaignReport\RepoYssCampaignReportController@updateTable'
    );
    Route::post(
        '/live_search',
        'RepoYssCampaignReport\RepoYssCampaignReportController@liveSearch'
    );
    Route::get(
        '/export_excel',
        'RepoYssCampaignReport\RepoYssCampaignReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoYssCampaignReport\RepoYssCampaignReportController@exportToCsv'
    );
});
