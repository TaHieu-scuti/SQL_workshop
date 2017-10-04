<?php

namespace App\Http\Controllers\RepoYssCampaignReport;

use App\Http\Controllers\AbstractReportController;
use App\Model\RepoYssCampaignReportCost;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class RepoYssCampaignReportController extends AbstractReportController
{
    const TIME_PERIOD_TITLE = 'timePeriodTitle';
    const GRAPH_COLUMN_NAME = 'graphColumnName';
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';
    const STATUS_TITLE = 'statusTitle';
    const COLUMN_SORT = 'columnSort';
    const SORT = 'sort';
    const SUMMARY_REPORT = 'summaryReport';
    const SESSION_KEY_PREFIX = 'campaignReport.';
    const SESSION_KEY_FIELD_NAME = self::SESSION_KEY_PREFIX . 'fieldName';
    const SESSION_KEY_TIME_PERIOD_TITLE = self::SESSION_KEY_PREFIX. self::TIME_PERIOD_TITLE;
    const SESSION_KEY_STATUS_TITLE = self::SESSION_KEY_PREFIX . self::STATUS_TITLE;
    const SESSION_KEY_START_DAY = self::SESSION_KEY_PREFIX . self::START_DAY;
    const SESSION_KEY_END_DAY = self::SESSION_KEY_PREFIX . self::END_DAY;
    const SESSION_KEY_PAGINATION = self::SESSION_KEY_PREFIX . 'pagination';
    const SESSION_KEY_GRAPH_COLUMN_NAME = self::SESSION_KEY_PREFIX . self::GRAPH_COLUMN_NAME;
    const SESSION_KEY_COLUMN_SORT = self::SESSION_KEY_PREFIX . self::COLUMN_SORT;
    const SESSION_KEY_SORT = self::SESSION_KEY_PREFIX . self::SORT;
    const SESSION_KEY_SUMMARY_REPORT = self::SESSION_KEY_PREFIX . self::SUMMARY_REPORT;

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';

    const COLUMNS_FOR_FILTER = 'columnsInModal';

    /** @var \App\Model\RepoYssCampaignReportCost */
    protected $model;

    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssCampaignReportCost $model
    ) {
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    /**
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function generateJSONErrorResponse(Exception $exception)
    {
        $errorObject = new StdClass;
        $errorObject->code = 500;
        $errorObject->error = $exception->getMessage();

        return $this->responseFactory->json($errorObject, 500);
    }

    private function initializeSession(array $columns)
    {
        $today = new DateTime;
        $endDay = $today->format('Y-m-d');
        $startDay = $today->modify('-90 days')->format('Y-m-d');
        $timePeriodTitle = "Last 90 days";
        $graphColumnName = 'clicks';
        $summaryReport = [
            'clicks',
            'impressions',
            'cost',
            'averageCpc',
            'averagePosition',
        ];
        session([self::SESSION_KEY_FIELD_NAME => $columns]);
        session([self::SESSION_KEY_TIME_PERIOD_TITLE => $timePeriodTitle]);
        session([self::SESSION_KEY_START_DAY => $startDay]);
        session([self::SESSION_KEY_END_DAY => $endDay]);
        session([self::SESSION_KEY_PAGINATION => 20]);
        session([self::SESSION_KEY_GRAPH_COLUMN_NAME => $graphColumnName]);
        session([self::SESSION_KEY_COLUMN_SORT => 'impressions']);
        session([self::SESSION_KEY_SORT => 'desc']);
        session([self::SESSION_KEY_SUMMARY_REPORT => $summaryReport]);
    }

    private function getDataForGraph()
    {
        $data = $this->model->newGetDataForGraph($graphColumnName, $startDay, $endDay);
        return $data;
    }

    public function index()
    {
        $allColumns = $this->model->getColumnNames();
        $unpossibleColumnsDisplay = [
            'exeDate',
            'startDate',
            'endDate',
            'account_id',
            'campaignID',
            'campaignName',
            'campaignDistributionSettings',
            'campaignDistributionStatus',
            'campaignStartDate',
            'campaignEndDate',
            'trackingURL',
            'customParameters',
            'campaignTrackingID',
            'network',
            'device',
            'day',
            'dayOfWeek',
            'quarter',
            'month',
            'week',
            'hourofday',
            'campaignType'
        ];
        $availableColumns = $this->model->unsetColumns($allColumns, $unpossibleColumnsDisplay);
        $modalAndSearchColumnsArray = $availableColumns;
        array_unshift($availableColumns, 'accountName');
        if (!session('campaignReport')) {
            $this->initializeSession($availableColumns);
        }
        $prefixRoute = request()->route()->getPrefix();
        return view('yssCampaignReport.index', [
            'prefixRoute' => $prefixRoute
        ]);
    }

    public function displayGraph(Request $request)
    {
        $page_prefix = $request->route()->getPrefix();
        $data = $this->getDataForGraph();
    }
}
