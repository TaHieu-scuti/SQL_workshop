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
        return redirect('client-list');
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
    Route::post(
        '/display-graph',
        'RepoYssAccountReport\RepoYssAccountReportController@displayGraph'
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

    Route::get(
        '/getDataForLayouts',
        'RepoYssAccountReport\RepoYssAccountReportController@getDataForLayouts'
    );
});

Route::prefix('campaign-report')->group(function () {
    Route::get(
        '/',
        'RepoYssCampaignReport\RepoYssCampaignReportController@index'
    )->name('campaign-report');
    Route::post(
        '/display-graph',
        'RepoYssCampaignReport\RepoYssCampaignReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssCampaignReport\RepoYssCampaignReportController@updateTable'
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

    Route::get(
        '/getDataForLayouts',
        'RepoYssCampaignReport\RepoYssCampaignReportController@getDataForLayouts'
    );
});

Route::prefix('adgroup-report')->group(function () {
    Route::get(
        '/',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@index'
    )->name('adgroup-report');
    Route::post(
        '/display-graph',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@updateTable'
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

    Route::get(
        '/getDataForLayouts',
        'RepoYssAdgroupReport\RepoYssAdgroupReportController@getDataForLayouts'
    );
});

Route::prefix('ad-report')->group(function () {
    Route::get(
        '/',
        'RepoYssAdReport\RepoYssAdReportController@index'
    )->name('ad-report');
    Route::post(
        '/display-graph',
        'RepoYssAdReport\RepoYssAdReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssAdReport\RepoYssAdReportController@updateTable'
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

    Route::get(
        '/getDataForLayouts',
        'RepoYssAdReport\RepoYssAdReportController@getDataForLayouts'
    );
});

Route::prefix('keyword-report')->group(function () {
    Route::get(
        '/',
        'RepoYssKeywordReport\RepoYssKeywordReportController@index'
    )->name('keyword-report');
    Route::post(
        '/display-graph',
        'RepoYssKeywordReport\RepoYssKeywordReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoYssKeywordReport\RepoYssKeywordReportController@updateTable'
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
    Route::get(
        '/export_search_query_csv',
        'RepoYssKeywordReport\RepoYssKeywordReportController@exportSearchQueryToCsv'
    );
    Route::get(
        '/export_search_query_excel',
        'RepoYssKeywordReport\RepoYssKeywordReportController@exportSearchQueryToExcel'
    );

    Route::get(
        '/getDataForLayouts',
        'RepoYssKeywordReport\RepoYssKeywordReportController@getDataForLayouts'
    );
});

Route::prefix('client-list')->group(function () {
    Route::get(
        '/',
        'Clients\ClientsController@index'
    )->name('client-list');
    Route::post(
        '/display-graph',
        'Clients\ClientsController@displayGraph'
    );
    Route::post(
        '/update-table',
        'Clients\ClientsController@updateTable'
    );
    Route::get(
        '/export_excel',
        'Clients\ClientsController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'Clients\ClientsController@exportToCsv'
    );
    Route::post(
        '/updateSession',
        'Clients\ClientsController@updateSessionID'
    );
    Route::get(
        '/export_search_query_csv',
        'Clients\ClientsController@exportSearchQueryToCsv'
    );
    Route::get(
        '/export_search_query_excel',
        'Clients\ClientsController@exportSearchQueryToExcel'
    );
    Route::get(
        '/getDataForLayouts',
        'Clients\ClientsController@getDataForLayouts'
    );
});

Route::get('language/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});
