<?php

namespace App\Http\Controllers\RepoYssCampaignReport;

use App\Http\Controllers\AbstractReportController;
use App\Model\RepoYssCampaignReportCost;
use App\Model\RepoAdwCampaignReportCost;
use App\Model\RepoYdnCampaignReport;

use App\Model\RepoYssPrefectureReportCost;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

use DateTime;
use Exception;

class RepoYssCampaignReportController extends AbstractReportController
{
    const TIME_PERIOD_TITLE = 'timePeriodTitle';
    const STATUS_TITLE = 'statusTitle';
    const GRAPH_COLUMN_NAME = 'graphColumnName';
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';
    const COLUMN_SORT = 'columnSort';
    const SORT = 'sort';
    const CAMPAIGN_ID = 'campaignID';
    const SUMMARY_REPORT = 'summaryReport';
    const SESSION_KEY_PREFIX = 'campaignReport.';
    const SESSION_KEY_FIELD_NAME = self::SESSION_KEY_PREFIX . 'fieldName';
    const SESSION_KEY_TIME_PERIOD_TITLE = self::SESSION_KEY_PREFIX. self::TIME_PERIOD_TITLE;
    const SESSION_KEY_ACCOUNT_STATUS = self::SESSION_KEY_PREFIX . 'accountStatus';
    const SESSION_KEY_STATUS_TITLE = self::SESSION_KEY_PREFIX . self::STATUS_TITLE;
    const SESSION_KEY_START_DAY = self::SESSION_KEY_PREFIX . self::START_DAY;
    const SESSION_KEY_END_DAY = self::SESSION_KEY_PREFIX . self::END_DAY;
    const SESSION_KEY_PAGINATION = self::SESSION_KEY_PREFIX . 'pagination';
    const SESSION_KEY_GRAPH_COLUMN_NAME = self::SESSION_KEY_PREFIX . self::GRAPH_COLUMN_NAME;
    const SESSION_KEY_COLUMN_SORT = self::SESSION_KEY_PREFIX . self::COLUMN_SORT;
    const SESSION_KEY_SORT = self::SESSION_KEY_PREFIX . self::SORT;
    const SESSION_KEY_SUMMARY_REPORT = self::SESSION_KEY_PREFIX . self::SUMMARY_REPORT;
    const SESSION_KEY_PREFIX_ROUTE = '/campaign-report';
    const SESSION_KEY_GROUPED_BY_FIELD = self::SESSION_KEY_PREFIX . 'groupedByField';

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';
    const GROUPED_BY_FIELD = 'campaignName';
    const ADW_GROUPED_BY_FIELD = 'campaign';
    const PREFIX_ROUTE = 'prefixRoute';
    const PREFECTURE = 'prefecture';

    const COLUMNS_FOR_FILTER = 'columnsInModal';
    const DEFAULT_COLUMNS = [
        'impressions',
        'clicks',
        'cost',
        'ctr',
        'averageCpc',
        'averagePosition'
    ];

    /**
     * @var \App\Model\RepoYssCampaignReportCost
     */
    protected $model;

    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssCampaignReportCost $model
    ) {
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    public function index()
    {
        $engine = $this->updateModel();
        $defaultColumns = self::DEFAULT_COLUMNS;
        if ($engine === 'yss' || $engine === 'ydn') {
            array_unshift($defaultColumns, self::GROUPED_BY_FIELD, self::CAMPAIGN_ID);
        } elseif ($engine === 'adw') {
            array_unshift($defaultColumns, self::ADW_GROUPED_BY_FIELD, self::CAMPAIGN_ID);
        }
        if (!session('campaignReport')) {
            $this->initializeSession($defaultColumns);
        }
        //update column fieldnames and grouped by field when change engine
        $this->updateGroupByFieldWhenSessionEngineChange($defaultColumns);
        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === self::PREFECTURE) {
            $this->model = new RepoYssPrefectureReportCost;
        }
        $this->checkoutSessionFieldName();
        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();

        return view(
            'yssCampaignReport.index',
            [
                self::KEY_PAGINATION => session(self::SESSION_KEY_PAGINATION),
                self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME), // field names which show on top of table
                self::REPORTS => $dataReports, // data that returned from query
                self::COLUMNS => $defaultColumns, // all columns that show up in modal
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TIME_PERIOD_TITLE => session(self::SESSION_KEY_TIME_PERIOD_TITLE),
                self::STATUS_TITLE => session(self::SESSION_KEY_STATUS_TITLE),
                self::START_DAY => session(self::SESSION_KEY_START_DAY),
                self::END_DAY => session(self::SESSION_KEY_END_DAY),
                // all columns that show columns live search
                self::COLUMNS_FOR_LIVE_SEARCH => self::DEFAULT_COLUMNS,
                self::TOTAL_DATA_ARRAY => $totalDataArray, // total data of each field
                self::COLUMNS_FOR_FILTER => self::DEFAULT_COLUMNS,
                self::SUMMARY_REPORT => $summaryReportData,
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
                self::GRAPH_COLUMN_NAME => session(self::SESSION_KEY_GRAPH_COLUMN_NAME),
            ]
        );
    }

    public function updateTable(Request $request)
    {
        $this->updateModel();
        $columns = $this->model->getColumnNames();
        if (!session('campaignReport')) {
            $this->initializeSession($columns);
        }
        $this->updateSessionData($request);

        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === self::PREFECTURE) {
            $this->model = new RepoYssPrefectureReportCost;
        }

        if ($request->specificItem === self::PREFECTURE) {
            session()->put([self::SESSION_KEY_GROUPED_BY_FIELD => self::PREFECTURE]);
            $this->model = new RepoYssPrefectureReportCost;
        }

        $reports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();

        $summaryReportLayout = view('layouts.summary_report', [self::SUMMARY_REPORT => $summaryReportData])->render();
        $tableDataLayout = view(
            'layouts.table_data',
            [
                self::REPORTS => $reports,
                self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME),
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TOTAL_DATA_ARRAY => $totalDataArray,
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
            ]
        )->render();
        // if no data found
        // display no data found message on table
        if ($reports->total() !== 0) {
            $this->displayNoDataFoundMessageOnTable = false;
        }
        return $this->responseFactory->json(
            [
                'summaryReportLayout' => $summaryReportLayout,
                'tableDataLayout' => $tableDataLayout,
                'displayNoDataFoundMessageOnTable' => $this->displayNoDataFoundMessageOnTable
            ]
        );
    }

    public function displayGraph(Request $request)
    {
        $this->updateModel();
        $this->updateSessionData($request);
        $timePeriodLayout = view('layouts.time-period')
            ->with(self::START_DAY, session(self::SESSION_KEY_START_DAY))
            ->with(self::END_DAY, session(self::SESSION_KEY_END_DAY))
            ->with(self::TIME_PERIOD_TITLE, session(self::SESSION_KEY_TIME_PERIOD_TITLE))
            ->render();
        $statusLayout = view('layouts.status-title')
            ->with(self::STATUS_TITLE, session(self::SESSION_KEY_STATUS_TITLE))
            ->render();
        try {
            $data = $this->getDataForGraph();
        } catch (Exception $exception) {
            return $this->generateJSONErrorResponse($exception);
        }
        foreach ($data as $value) {
            // if data !== null, display on graph
            // else, display "no data found" image
            if ($value['data'] !== null) {
                $this->displayNoDataFoundMessageOnGraph = false;
            }
        }
        return $this->responseFactory->json(
            [
                'data' => $data,
                'field' => session(self::SESSION_KEY_GRAPH_COLUMN_NAME),
                'timePeriodLayout' => $timePeriodLayout,
                'statusLayout' => $statusLayout,
                'displayNoDataFoundMessageOnGraph' => $this->displayNoDataFoundMessageOnGraph
            ]
        );
    }

    public function updateSessionID(Request $request)
    {
        $this->updateSessionData($request);
    }

    public function updateModel()
    {
        $engine = session(self::SESSION_KEY_ENGINE);
        if ($engine === 'yss') {
            $this->model = new RepoYssCampaignReportCost;
        } elseif ($engine === 'adw') {
            $this->model = new RepoAdwCampaignReportCost;
        } elseif ($engine === 'ydn') {
            $this->model = new RepoYdnCampaignReport;
        }
        return $engine;
    }
}
