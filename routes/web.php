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

    Route::post(
        '/updateSession',
        'RepoYssAccountReport\RepoYssAccountReportController@updateSessionID'
    );
});

Route::prefix('campaign-report')->group(function () {
    Route::get(
        '/',
        'RepoYssCampaignReport\RepoYssCampaignReportController@index'
    )->name('campaign-report');
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
    Route::post(
        '/updateSession',
        'RepoYssCampaignReport\RepoYssCampaignReportController@updateSessionID'
    );
});

Route::prefix('adgroup-report')->group(function () {
    Route::get(
        '/',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@index'
    )->name('adgroup-report');
    Route::get(
        '/display-graph',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@displayGraph'
    );
    Route::post(
        '/display-graph',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@updateTable'
    );
    Route::post(
        '/live_search',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@liveSearch'
    );
    Route::get(
        '/export_excel',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@exportToCsv'
    );
    Route::post(
        '/updateSession',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@updateSessionID'
    );
});

Route::prefix('ad-report')->group(function () {
    Route::get(
        '/',
        'RepoYssAdReport\RepoYssAdReportController@index'
    )->name('ad-report');
    Route::get(
        '/display-graph',
        'RepoYssAdReport\RepoYssAdReportController@displayGraph'
    );
    Route::post(
        '/display-graph',
        'RepoYssAdReport\RepoYssAdReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssAdReport\RepoYssAdReportController@updateTable'
    );
    Route::post(
        '/live_search',
        'RepoYssAdReport\RepoYssAdReportController@liveSearch'
    );
    Route::get(
        '/export_excel',
        'RepoYssAdReport\RepoYssAdReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoYssAdReport\RepoYssAdReportController@exportToCsv'
    );

    Route::post(
        '/updateSession',
        'RepoYssAdReport\RepoYssAdReportController@updateSessionID'
    );
});

Route::prefix('keyword-report')->group(function () {
    Route::get(
        '/',
        'RepoYssKeywordReport\RepoYssKeywordReportController@index'
    )->name('keyword-report');
    Route::get(
        '/display-graph',
        'RepoYssKeywordReport\RepoYssKeywordReportController@displayGraph'
    );
    Route::post(
        '/display-graph',
        'RepoYssKeywordReport\RepoYssKeywordReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssKeywordReport\RepoYssKeywordReportController@updateTable'
    );
    Route::post(
        '/live_search',
        'RepoYssKeywordReport\RepoYssKeywordReportController@liveSearch'
    );
    Route::get(
        '/export_excel',
        'RepoYssKeywordReport\RepoYssKeywordReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoYssKeywordReport\RepoYssKeywordReportController@exportToCsv'
    );
    Route::post(
        '/updateSession',
        'RepoYssKeywordReport\RepoYssKeywordReportController@updateSessionID'
    );
});
