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
        return redirect('agency-report');
    }
);

Route::prefix('agency-report')->group(function () {
    Route::get(
        '/',
        'AgencyController\AgencyController@index'
    )->name('agency-report');
    Route::post(
        '/update-table',
        'AgencyController\AgencyController@updateTable'
    );
    Route::post(
        '/display-graph',
        'AgencyController\AgencyController@displayGraph'
    );
    Route::get(
        '/export_excel',
        'AgencyController\AgencyController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'AgencyController\AgencyController@exportToCsv'
    );
    Route::post(
        '/updateSession',
        'AgencyController\AgencyController@updateSessionID'
    );

    Route::get(
        '/getDataForLayouts',
        'AgencyController\AgencyController@getDataForLayouts'
    );
});

Route::prefix('account_report')->group(function () {
    Route::get(
        '/',
        'RepoAccountReport\RepoAccountReportController@index'
    )->name('account_report');
    Route::post(
        '/update-table',
        'RepoAccountReport\RepoAccountReportController@updateTable'
    );
    Route::post(
        '/display-graph',
        'RepoAccountReport\RepoAccountReportController@displayGraph'
    );
    Route::get(
        '/export_excel',
        'RepoAccountReport\RepoAccountReportController@exportToExcel'
    );

    Route::get(
        '/export_csv',
        'RepoAccountReport\RepoAccountReportController@exportToCsv'
    );

    Route::post(
        '/updateSession',
        'RepoAccountReport\RepoAccountReportController@updateSessionID'
    );

    Route::get(
        '/getDataForLayouts',
        'RepoAccountReport\RepoAccountReportController@getDataForLayouts'
    );
});

Route::prefix('direct-client-report')->group(function () {
    Route::get(
        '/',
        'DirectClient\DirectClientController@index'
    )->name('direct-client-report');
    Route::post(
        '/update-table',
        'DirectClient\DirectClientController@updateTable'
    );
    Route::post(
        '/display-graph',
        'DirectClient\DirectClientController@displayGraph'
    );
    Route::get(
        '/export_excel',
        'DirectClient\DirectClientController@exportToExcel'
    );

    Route::get(
        '/export_csv',
        'DirectClient\DirectClientController@exportToCsv'
    );

    Route::post(
        '/updateSession',
        'DirectClient\DirectClientController@updateSessionID'
    );

    Route::get(
        '/getDataForLayouts',
        'DirectClient\DirectClientController@getDataForLayouts'
    );
});

Route::prefix('campaign-report')->group(function () {
    Route::get(
        '/',
        'RepoCampaignReport\RepoCampaignReportController@index'
    )->name('campaign-report');
    Route::post(
        '/display-graph',
        'RepoCampaignReport\RepoCampaignReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoCampaignReport\RepoCampaignReportController@updateTable'
    );
    Route::get(
        '/export_excel',
        'RepoCampaignReport\RepoCampaignReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoCampaignReport\RepoCampaignReportController@exportToCsv'
    );
    Route::post(
        '/updateSession',
        'RepoCampaignReport\RepoCampaignReportController@updateSessionID'
    );

    Route::get(
        '/getDataForLayouts',
        'RepoCampaignReport\RepoCampaignReportController@getDataForLayouts'
    );
});

Route::prefix('adgroup-report')->group(function () {
    Route::get(
        '/',
        'RepoAdgroupReport\RepoAdgroupReportController@index'
    )->name('adgroup-report');
    Route::post(
        '/display-graph',
        'RepoAdgroupReport\RepoAdgroupReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoAdgroupReport\RepoAdgroupReportController@updateTable'
    );
    Route::get(
        '/export_excel',
        'RepoAdgroupReport\RepoAdgroupReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoAdgroupReport\RepoAdgroupReportController@exportToCsv'
    );
    Route::post(
        '/updateSession',
        'RepoAdgroupReport\RepoAdgroupReportController@updateSessionID'
    );

    Route::get(
        '/getDataForLayouts',
        'RepoAdgroupReport\RepoAdgroupReportController@getDataForLayouts'
    );
});

Route::prefix('ad-report')->group(function () {
    Route::get(
        '/',
        'RepoAdReport\RepoAdReportController@index'
    )->name('ad-report');
    Route::post(
        '/display-graph',
        'RepoAdReport\RepoAdReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoAdReport\RepoAdReportController@updateTable'
    );
    Route::get(
        '/export_excel',
        'RepoAdReport\RepoAdReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoAdReport\RepoAdReportController@exportToCsv'
    );

    Route::post(
        '/updateSession',
        'RepoAdReport\RepoAdReportController@updateSessionID'
    );

    Route::get(
        '/getDataForLayouts',
        'RepoAdReport\RepoAdReportController@getDataForLayouts'
    );
});

Route::prefix('keyword-report')->group(function () {
    Route::get(
        '/',
        'RepoKeywordReport\RepoKeywordReportController@index'
    )->name('keyword-report');
    Route::post(
        '/display-graph',
        'RepoKeywordReport\RepoKeywordReportController@displayGraph'
    );
    Route::post(
        '/update-table',
        'RepoKeywordReport\RepoKeywordReportController@updateTable'
    );
    Route::get(
        '/export_excel',
        'RepoKeywordReport\RepoKeywordReportController@exportToExcel'
    );
    Route::get(
        '/export_csv',
        'RepoKeywordReport\RepoKeywordReportController@exportToCsv'
    );
    Route::post(
        '/updateSession',
        'RepoKeywordReport\RepoKeywordReportController@updateSessionID'
    );
    Route::get(
        '/export_search_query_csv',
        'RepoKeywordReport\RepoKeywordReportController@exportSearchQueryToCsv'
    );
    Route::get(
        '/export_search_query_excel',
        'RepoKeywordReport\RepoKeywordReportController@exportSearchQueryToExcel'
    );

    Route::get(
        '/getDataForLayouts',
        'RepoKeywordReport\RepoKeywordReportController@getDataForLayouts'
    );
});

Route::prefix('client-report')->group(function () {
    Route::get(
        '/',
        'Clients\ClientsController@index'
    )->name('client-report');
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
        '/getDataForLayouts',
        'Clients\ClientsController@getDataForLayouts'
    );
});

Route::get('language/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::prefix('auth-account')->group(function () {
    Route::get(
        '/',
        'RepoAuthAccount\RepoAuthAccountController@index'
    )->name('auth-account');
    Route::get(
        '/config-account/{id}',
        'RepoAuthAccount\RepoAuthAccountController@config'
    )->name('config-account');
    Route::post(
        '/store-account',
        'RepoAuthAccount\RepoAuthAccountController@store'
    )->name('store-account');
    Route::post(
        '/update-account/{id}',
        'RepoAuthAccount\RepoAuthAccountController@update'
    )->name('update-account');
});

Route::get('/error404', 'ErrorController@error404');
