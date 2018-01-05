<?php

namespace App\Model;

use App\Http\Controllers\AbstractReportController;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;

class RepoYdnCampaignReport extends AbstractYdnReportModel
{
    const GROUPED_BY_FIELD_NAME = 'campaignName';
    const PAGE_ID = 'campaignID';

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }

    public function getAllYdnCampaign(
        $accountId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        return self::select('campaignID', 'campaignName')
            ->where(
                function ($query) use ($accountId, $engine) {
                    $this->addQueryConditions(
                        $query,
                        session(AbstractReportController::SESSION_KEY_CLIENT_ID),
                        $engine,
                        $accountId
                    );
                }
            )
            ->groupBy('campaignID', 'campaignName')->get();
    }
}
