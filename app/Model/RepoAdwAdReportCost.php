<?php
namespace App\Model;

use App\Model\AbstractAdwModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use App\AbstractReportModel;

class RepoAdwAdReportCost extends AbstractAdwModel
{
    protected $preFixRoute = '';

    const GROUPED_BY_FIELD_NAME = 'ad';
    const PAGE_ID = 'adID';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaign',
            'tableJoin' => 'repo_adw_ad_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adGroup',
            'tableJoin' => 'repo_adw_ad_report_cost',
            'columnId' => 'adGroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];

    const FIELDS = [
        'displayURL',
        'description'
    ];

    const FIELDS_ADGROUP_ADW = [
        'adGroupID',
        'adGroup'
    ];

    protected $table = "repo_adw_ad_report_cost";

    /**
     * @var bool
     */
    public $timestamps = false;

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adw_ad_conv_model = new RepoAdwAdReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $adw_ad_conv_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }

    protected function updateTemporaryTableWithConversion(
        $conversionPoints,
        $groupedByField,
        $startDay,
        $endDay,
        $engine,
        $clientId = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null,
        $tableName = ""
    ) {
        if (empty($tableName)) {
            $tableName = self::TABLE_TEMPORARY;
        }
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoAdwAdReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_adw_ad_report_conv.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
                ->where(
                    function (EloquentBuilder $query) use (
                        $convModel,
                        $startDay,
                        $endDay,
                        $engine,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    ) {
                        $convModel->getCondition(
                            $query,
                            $startDay,
                            $endDay,
                            $engine,
                            $clientId,
                            $accountId,
                            $campaignId,
                            $adGroupId,
                            $adReportId,
                            $keywordId
                        );
                    }
                )->groupBy($this->groupBy);

            DB::update(
                'update '.$tableName.', ('
                .$this->getBindingSql($queryGetConversion).')AS tbl set conversions'.$key.' = tbl.conversions where '
                .$tableName.'.'.$groupedByField.' = tbl.'.$groupedByField
            );
        }
    }

    protected function updateTemporaryTableWithCallTracking(
        $adGainerCampaigns,
        $groupedByField,
        $startDay,
        $endDay,
        $engine,
        $clientId = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null,
        $tableName = ""
    ) {
        $groupedField = 'adgroupID';
        if (empty($tableName)) {
            $tableName = self::TABLE_TEMPORARY;
            $groupedField = 'adID';
        }
        $campaignIdAdgainer = $this->getCampaignIdAdgainer($clientId, $accountId, $campaignId, $adGroupId);
        $phoneNumbers = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());

        $phoneTimeUseModel = new PhoneTimeUse();
        $phoneTimeUseTableName = $phoneTimeUseModel->getTable();
        $campaignModel = new Campaign();
        $campaignForPhoneTimeUse = $campaignModel->getCustomForPhoneTimeUse($campaignIdAdgainer);

        foreach ($campaignForPhoneTimeUse as $i => $campaign) {
            $customField = $this->getFieldName($campaign, 'creative');

            $builder = $phoneTimeUseModel->select(
                [
                    DB::raw('count(id) AS id'),
                    $customField
                ]
            )
                ->whereRaw($customField.' NOT LIKE ""')
                ->where('source', '=', $engine)
                ->whereRaw('traffic_type = "AD"')
                ->whereIn('phone_number', $phoneNumbers)
                ->whereIn('utm_campaign', $utmCampaignList)
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $endDay, $phoneTimeUseTableName) {
                        $this->addConditonForDate($query, $phoneTimeUseTableName, $startDay, $endDay);
                    }
                )
                ->groupBy($customField);
            DB::update(
                'update '.$tableName.', ('
                .$this->getBindingSql($builder).') AS tbl set call'.$i.' = tbl.id where '
                .$tableName.'.'.$groupedField.' = tbl.'.$customField
            );
        }
    }

    public function getQueryForDataTable(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null,
        $conversionPoints = null,
        $adGainerCampaigns = null
    ) {
        $this->conversionPoints = $conversionPoints;
        $this->adGainerCampaigns = $adGainerCampaigns;
        $fieldNames = $this->checkConditionFieldName($fieldNames);

        $this->preFixRoute = 'adgroup';
        $builder = AbstractReportModel::getBuilderForGetDataForTable(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $columnSort,
            $sort,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        if ($this->isConv || $this->isCallTracking) {
            $columns = $fieldNames;
            if (!in_array('adgroupID', $columns)) {
                array_unshift($columns, 'adgroupID');
            }

            if (static::PAGE_ID !== 'campaignID') {
                $columns  = $this->higherSelectionFields($columns, $campaignId, $adGroupId, 'adgroup');
            }

            $this->createTemporaryTable(
                $columns,
                $this->isConv,
                $this->isCallTracking,
                $conversionPoints,
                $adGainerCampaigns,
                'adgroup'
            );
            $columns = $this->unsetColumns(
                $columns,
                array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, self::FIELDS)
            );

            $columns = array_keys($this->updateFieldNames($columns));
            DB::insert('INSERT into '.self::TABLE_TEMPORARY_AD.' ('.implode(', ', $columns).') '
                . $this->getBindingSql($builder));

            if ($this->isConv) {
                $this->updateTemporaryTableWithConversion(
                    $conversionPoints,
                    $groupedByField,
                    $startDay,
                    $endDay,
                    $engine,
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId,
                    self::TABLE_TEMPORARY_AD
                );
            }

            if ($this->isCallTracking) {
                $this->updateTemporaryTableWithCallTracking(
                    $adGainerCampaigns,
                    $groupedByField,
                    $startDay,
                    $endDay,
                    $engine,
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId,
                    self::TABLE_TEMPORARY_AD
                );
            }
            $arr = [];
            if (in_array('impressionShare', $fieldNames)) {
                $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
            }
            $fields = $this->unsetColumns($fieldNames, ['impressionShare']);
            $aggregated = $this->processGetAggregated(
                $fields,
                $groupedByField,
                $campaignId,
                $adGroupId,
                self::TABLE_TEMPORARY_AD
            );
            $builder = DB::table(self::TABLE_TEMPORARY_AD)
                ->select(array_merge($aggregated, $arr))
                ->groupby($groupedByField)
                ->orderBy($columnSort, $sort);
        }

        return $builder;
    }

    public function getQueryForCalculateData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $agencyId,
        $accountId,
        $clientId,
        $campaignId,
        $adGroupId,
        $adReportId,
        $keywordId,
        $conversionPoints,
        $adGainerCampaigns
    ) {
        $this->conversionPoints = $conversionPoints;
        $this->adGainerCampaigns = $adGainerCampaigns;
        $fieldNames = $this->checkConditionFieldName($fieldNames);

        $builder = AbstractReportModel::getBuilderForCalculateData(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        if ($this->isConv || $this->isCallTracking) {
            $arr = [];
            if (in_array('impressionShare', $fieldNames)) {
                $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
            }
            $fields = $this->unsetColumns($fieldNames, ['impressionShare']);
            $aggregated = $this->processGetAggregated(
                $fields,
                $groupedByField,
                $campaignId,
                $adGroupId,
                self::TABLE_TEMPORARY_AD
            );

            $builder = DB::table(self::TABLE_TEMPORARY_AD)->select(
                array_merge(
                    $aggregated,
                    $arr
                )
            );
        }

        return $builder;
    }

    public function getCampaignIdAdgainer($account_id, $accountId, $campaignId, $adGroupId)
    {
        return $this->select('campaign_id')
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }
}
