<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssKeywordReportCost extends AbstractReportModel
{
    const PAGE_ID = "keywordID";
    const GROUPED_BY_FIELD_NAME = 'keyword';
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_keyword_report_cost';

    public function getAllKeyword($accountId = null, $campaignId = null, $adgroupId = null, $keywordId = null)
    {
        $arrKeywords = [];

        $arrKeywords['all'] = 'All Keywords';

        $keywords = $this->select('keywordID', 'keyword')->where(
            function ($query) use ($accountId, $campaignId, $adgroupId, $keywordId) {
                $this->addQueryConditions(
                    $query,
                    Auth::user()->account_id,
                    $accountId,
                    $campaignId,
                    $adgroupId,
                    $keywordId
                );
            }
        )->get();

        if ($keywords) {
            foreach ($keywords as $key => $keyword) {
                $arrKeywords[$keyword->keywordID] = $keyword->keyword;
            }
        }

        return $arrKeywords;
    }
}
