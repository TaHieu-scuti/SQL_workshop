<?php

namespace App\Http\Controllers\RepoYssCampaignReport;

use App\Http\Controllers\AbstractReportController;
use App\Model\RepoYssCampaignReportCost;
use App\Model\RepoAdwCampaignReportCost;
use App\Model\RepoYdnCampaignReport;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class RepoYssCampaignReportController extends AbstractReportController
{
    const GRAPH_COLUMN_NAME = 'graphColumnName';
    const COLUMN_SORT = 'columnSort';
    const SORT = 'sort';
    const CAMPAIGN_ID = 'campaignID';
    const MEDIA_ID = self::CAMPAIGN_ID;
    const SUMMARY_REPORT = 'summaryReport';
    const SESSION_KEY_PREFIX = 'campaignReport.';
    const SESSION_KEY_FIELD_NAME = self::SESSION_KEY_PREFIX . 'fieldName';
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
    const SESSION_KEY_OLD_ENGINE = 'oldEngine';

    const COLUMNS_FOR_FILTER = 'columnsInModal';
    const DEFAULT_COLUMNS = [
        'impressions',
        'clicks',
        'cost',
        'ctr',
        'averageCpc',
        'averagePosition',
        'impressionShare',
        'web_cv',
        'web_cvr',
        'web_cpa',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
    ];

    /**
     * @var \App\Model\RepoYssCampaignReportCost
     */
    protected $model;

    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssCampaignReportCost $model
    ) {
        $this->middleware('engine');
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    public function index()
    {
        $engine = $this->updateModel();
        session()->put([self::SESSION_KEY_CAMPAIGNID => null]);
        $defaultColumns = self::DEFAULT_COLUMNS;
        if ($engine === 'yss' || $engine === 'ydn') {
            array_unshift($defaultColumns, self::GROUPED_BY_FIELD, self::CAMPAIGN_ID);
            if ($engine === 'ydn') {
                $defaultColumns = $this->model->unsetColumns($defaultColumns, ['impressionShare']);
            }
        } elseif ($engine === 'adw') {
            array_unshift($defaultColumns, self::ADW_GROUPED_BY_FIELD, self::CAMPAIGN_ID);
        }
        if (!session('campaignReport')) {
            $this->initializeSession($defaultColumns);
        }
        //update column fieldnames and grouped by field when change engine
        if ($this->checkoutConditionForUpdateColumn($engine)) {
            $this->updateGroupByFieldWhenSessionEngineChange($defaultColumns);
        }
        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === self::PREFECTURE) {
            $this->updateModelForPrefecture();
        }

        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === 'hourofday') {
            $this->updateModelForTimezone();
        }

        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === 'dayOfWeek') {
            $this->updateModelForDayOfWeek();
        }

        $this->checkoutSessionFieldName();
        return $this->responseFactory->view(
            'yssCampaignReport.index',
            [
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE
            ]
        );
    }

    public function getDataForLayouts()
    {
        $this->updateModel();
        $this->getModelForPrefecture();
        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $fieldNames = session(self::SESSION_KEY_FIELD_NAME);

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
                self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME),
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
                self::FIELD_NAMES => $fieldNames
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
        $this->updateModel();
        $columns = $this->model->getColumnNames();
        if (!session('campaignReport')) {
            $this->initializeSession($columns);
        }
        $this->updateSessionData($request);

        if ($request->specificItem === self::PREFECTURE) {
            session()->put([self::SESSION_KEY_GROUPED_BY_FIELD => self::PREFECTURE]);
            $this->updateModelForPrefecture();
        }

        if ($request->specificItem === 'hourofday') {
            session()->put([self::SESSION_KEY_GROUPED_BY_FIELD => 'hourofday']);
            $this->updateModelForTimezone();
        }

        if ($request->specificItem === 'dayOfWeek') {
            session()->put([self::SESSION_KEY_GROUPED_BY_FIELD => 'dayOfWeek']);
            $this->updateModelForDayOfWeek();
        }

        $fieldNames = session(self::SESSION_KEY_FIELD_NAME);
        if ($request->specificItem === self::PREFECTURE) {
            session()->put([self::SESSION_KEY_GROUPED_BY_FIELD => self::PREFECTURE]);
            $fieldNames = $this->model->unsetColumns($fieldNames, ['impressionShare']);
            session()->put([self::SESSION_KEY_FIELD_NAME => $fieldNames]);
            $this->updateModelForPrefecture();
        } else {
            session()->put([self::SESSION_KEY_FIELD_NAME => $fieldNames]);
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
