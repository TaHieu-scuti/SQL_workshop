<?php

namespace App\Model;

use App\AbstractReportModel;
use Auth;

class RepoYdnAdgroupReport extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'adgroupName';
    const PAGE_ID = 'adgroupID';

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;

    public function getAllYdnAdgroup(
        $accountId = null,
        $campaignId = null,
        $adgroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        return self::select('adgroupID as adgroupID', 'adgroupName as adgroupName')
            ->where(
                function ($query) use ($accountId, $campaignId, $adgroupId, $adReportId) {
                    $this->addQueryConditions(
                        $query,
                        Auth::user()->account_id,
                        $accountId,
                        $campaignId,
                        $adgroupId,
                        $adReportId
                    );
                }
            )
            ->get();
    }
}
