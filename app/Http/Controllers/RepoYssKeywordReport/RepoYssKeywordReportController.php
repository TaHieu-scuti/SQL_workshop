<?php

namespace App\Http\Controllers\RepoYssKeywordReport;

use App\Http\Controllers\AbstractReportController;
use App\Model\RepoYssKeywordReportCost;
use App\Model\RepoAdwKeywordReportCost;
use App\Model\RepoYssPrefectureReportCost;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use DateTime;
use Exception;

class RepoYssKeywordReportController extends AbstractReportController
{
    const TIME_PERIOD_TITLE = 'timePeriodTitle';
    const STATUS_TITLE = 'statusTitle';
    const GRAPH_COLUMN_NAME = 'graphColumnName';
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';
    const COLUMN_SORT = 'columnSort';
    const SORT = 'sort';
    const MEDIA_ID = 'keywordID';
    const SUMMARY_REPORT = 'summaryReport';
    const SESSION_KEY_PREFIX = 'keywordReport.';
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
    const SESSION_KEY_PREFIX_ROUTE = '/keyword-report';
    const SESSION_KEY_GROUPED_BY_FIELD = self::SESSION_KEY_PREFIX . 'groupedByField';

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';
    const GROUPED_BY_FIELD = 'keyword';
    const ADW_GROUPED_BY_FIELD = self::GROUPED_BY_FIELD;
    const PREFIX_ROUTE = 'prefixRoute';

    const COLUMNS_FOR_FILTER = 'columnsInModal';
    const DEFAULT_COLUMNS = [
        'matchType',
        'impressions',
        'clicks',
        'cost',
        'ctr',
        'averageCpc',
        'averagePosition',
        'impressionShare'
    ];

    /**
     * @var \App\Model\RepoYssKeywordReportCost
     */
    protected $model;

    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssKeywordReportCost $model
    ) {
        $this->middleware('engine');
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    public function index()
    {
        $engine = $this->updateModel();
        $defaultColumns = self::DEFAULT_COLUMNS;
        if ($engine === null || $engine === 'yss') {
            array_unshift($defaultColumns, self::GROUPED_BY_FIELD);
        } elseif ($engine === 'adw') {
            array_unshift($defaultColumns, self::ADW_GROUPED_BY_FIELD);
        }

        if (!session('keywordReport')) {
            $this->initializeSession($defaultColumns);
        }
        //update column fieldnames and grouped by field when change engine
        $this->updateGroupByFieldWhenSessionEngineChange($defaultColumns);
        $this->checkoutSessionFieldName();
        return $this->responseFactory->view(
            'yssKeywordReport.index',
            [
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE
            ]
        );
    }

    public function getDataForLayouts()
    {
        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        //add more columns higher layer to fieldnames
        $tableColumns = $this->updateTableColumns($dataReports);

        if ($engine === 'yss') {
            $tableColumns[] = 'call_tracking';
            $tableColumns[] = 'call_cvr';
            $tableColumns[] = 'call_cpa';
        }

        $summaryReportLayout = view(
            'layouts.summary_report',
            [
                self::SUMMARY_REPORT => $summaryReportData
            ]
        )->render();
        $tableDataLayout = view(
            'layouts.table_data',
            [
                self::REPORTS => $dataReports,
                self::FIELD_NAMES => $tableColumns,
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TOTAL_DATA_ARRAY => $totalDataArray,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
            ]
        )->render();
        $fieldsOnModal = view(
            'layouts.fields_on_modal',
            [
                self::COLUMNS_FOR_FILTER => self::DEFAULT_COLUMNS,
                self::FIELD_NAMES => $tableColumns
            ]
        )->render();
        $columnForLiveSearch = view(
            'layouts.graph_items',
            [
                self::COLUMNS_FOR_LIVE_SEARCH => self::DEFAULT_COLUMNS,
                self::GRAPH_COLUMN_NAME => session(self::SESSION_KEY_GRAPH_COLUMN_NAME)
            ]
        )->render();
        $timePeriodLayout = view('layouts.time-period')
            ->with(self::START_DAY, session(self::SESSION_KEY_START_DAY))
            ->with(self::END_DAY, session(self::SESSION_KEY_END_DAY))
            ->with(self::TIME_PERIOD_TITLE, session(self::SESSION_KEY_TIME_PERIOD_TITLE))
            ->render();
        $statusLayout = view('layouts.status-title')
            ->with(self::STATUS_TITLE, session(self::SESSION_KEY_STATUS_TITLE))
            ->render();
        $keyPagination = view(
            'layouts.key_pagination_per_page',
            [
                self::KEY_PAGINATION => session(self::SESSION_KEY_PAGINATION)
            ]
        )->render();

        return $this->responseFactory->json(
            [
                'summaryReportLayout' => $summaryReportLayout,
                'tableDataLayout' => $tableDataLayout,
                'fieldsOnModal' => $fieldsOnModal,
                'coloumnForLiveSearch' => $columnForLiveSearch,
                'timePeriodLayout' => $timePeriodLayout,
                'statusLayout' => $statusLayout,
                'keyPagination' => $keyPagination
            ]
        );
    }

    public function updateTable(Request $request)
    {
        $engine = $this->updateModel();
        $this->updateSessionData($request);

        if ($request->specificItem === 'prefecture') {
            $this->model = new RepoYssPrefectureReportCost;
        }

        $reports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $summaryReportLayout = view('layouts.summary_report', [self::SUMMARY_REPORT => $summaryReportData])->render();
        $tableColumns = $this->updateTableColumns($reports);
        if ($engine === 'yss') {
            $tableColumns[] = 'call_tracking';
            $tableColumns[] = 'call_cvr';
            $tableColumns[] = 'call_cpa';
        }
        $tableDataLayout = view(
            'layouts.table_data',
            [
            self::REPORTS => $reports,
            self::FIELD_NAMES => $tableColumns,
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

    public function updateModel()
    {
        $engine = session(self::SESSION_KEY_ENGINE);
        if ($engine === 'yss') {
            $this->model = new RepoYssKeywordReportCost;
        } elseif ($engine === 'adw') {
            $this->model = new RepoAdwKeywordReportCost;
        }
        return $engine;
    }

    private function updateTableColumns(LengthAwarePaginator $dataReports)
    {
        $tableColumns = session(self::SESSION_KEY_FIELD_NAME);
        if (!empty($dataReports[0]->adgroupName)) {
            array_unshift($tableColumns, 'adgroupName');
        }
        if (!empty($dataReports[0]->campaignName)) {
            array_unshift($tableColumns, 'campaignName');
        }
        return $tableColumns;
    }
}
