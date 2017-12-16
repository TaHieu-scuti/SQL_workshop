<?php
//Agency
Breadcrumbs::register(
    'agency-report',
    function ($breadcrumbs) {
        $breadcrumbs->push('Agency', route('agency-report'));
    }
);

//Client
Breadcrumbs::register(
    'client-report',
    function ($breadcrumbs) {
        $breadcrumbs->parent('agency-report');
        $breadcrumbs->push('Client', route('client-report'));
    }
);

//Client->Account
Breadcrumbs::register(
    'account_report',
    function ($breadcrumbs) {
        $breadcrumbs->parent('client-report');
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
