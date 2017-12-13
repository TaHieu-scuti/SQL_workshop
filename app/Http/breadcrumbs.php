<?php

//Account
Breadcrumbs::register(
    'account_report',
    function ($breadcrumbs) {
        $breadcrumbs->push('Account', route('account_report'));
    }
);

//Account -> Campaign
Breadcrumbs::register(
    'campaign-report',
    function ($breadcrumbs) {
        $breadcrumbs->parent('account_report');
        $breadcrumbs->push('Campaign', route('campaign-report'));
    }
);

//Account -> Campaign -> AdGroup
Breadcrumbs::register(
    'adgroup-report',
    function ($breadcrumbs) {
        $breadcrumbs->parent('campaign-report');
        $breadcrumbs->push('AdGroup', route('adgroup-report'));
    }
);
Breadcrumbs::register(
    'client-list',
    function ($breadcrumbs) {
        $breadcrumbs->push('Client', route('client-list'));
    }
);
