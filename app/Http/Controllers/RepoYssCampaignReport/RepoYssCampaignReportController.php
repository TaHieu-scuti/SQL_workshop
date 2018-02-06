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
    const SESSION_KEY_ALL_FIELD_NAME = self::SESSION_KEY_PREFIX . 'allFieldName';
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
        'conversions',
        '[conversionValues]',
        'web_cv',
        'web_cvr',
        'web_cpa',
        '[phoneNumberValues]',
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

    private $isObjectStdClass = true;

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
            array_unshift($defaultColumns, self::CAMPAIGN_ID, self::GROUPED_BY_FIELD);
            if ($engine === 'ydn') {
                $defaultColumns = $this->model->unsetColumns($defaultColumns, ['impressionShare']);
            }
        } elseif ($engine === 'adw') {
            array_unshift($defaultColumns, self::CAMPAIGN_ID, self::ADW_GROUPED_BY_FIELD);
        }
        if (!session('campaignReport')) {
            $this->initializeSession($defaultColumns);
        }
        //update column fieldnames and grouped by field when change engine
        if ($this->checkoutConditionForUpdateColumn($engine)) {
            $this->updateGroupByFieldWhenSessionEngineChange($defaultColumns);
        }

        $this->checkoutSessionFieldName();
        return $this->responseFactory->view(
            'yssCampaignReport.index',
            [
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                self::COLUMNS_FOR_LIVE_SEARCH => self::DEFAULT_COLUMNS_GRAPH,
                self::GRAPH_COLUMN_NAME => session(self::SESSION_KEY_GRAPH_COLUMN_NAME)
            ]
        );
    }

    public function getDataForLayouts()
    {
        $this->updateModel();
        $this->updateSpecificModel();

        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $fieldNames = session(self::SESSION_KEY_FIELD_NAME);
        $columns = array_keys((array) $dataReports[0]);
        if (is_object($dataReports[0]) && property_exists($dataReports[0], 'table')) {
            $columns = array_keys($dataReports[0]->getAttributes());
            $this->isObjectStdClass = false;
        }
        $columns = $this->removeUnnecessaryFields($columns);
        session([self::SESSION_KEY_ALL_FIELD_NAME => $columns]);
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
                self::FIELD_NAMES => $columns,
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TOTAL_DATA_ARRAY => $totalDataArray,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
                'isObjectStdClass' => $this->isObjectStdClass
            ]
        )->render();
        $fieldsOnModal = view(
            'layouts.fields_on_modal',
            [
                self::COLUMNS_FOR_FILTER => self::DEFAULT_COLUMNS,
                self::FIELD_NAMES => $fieldNames
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

        //after update session, update specific model
        $this->updateSpecificModel();

        $reports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $summaryReportLayout = view(
            'layouts.summary_report',
            [self::SUMMARY_REPORT => $summaryReportData]
        )->render();

        $columns = array_keys((array) $reports[0]);
        if (is_object($reports[0]) && property_exists($reports[0], 'table')) {
            $columns = array_keys($reports[0]->getAttributes());
            $this->isObjectStdClass = false;
        }
        $columns = $this->removeUnnecessaryFields($columns);
        session([self::SESSION_KEY_ALL_FIELD_NAME => $columns]);

        $tableDataLayout = view(
            'layouts.table_data',
            [
                self::REPORTS => $reports,
                self::FIELD_NAMES => $columns,
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TOTAL_DATA_ARRAY => $totalDataArray,
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
                'isObjectStdClass' => $this->isObjectStdClass
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

    private function removeUnnecessaryFields($columnTable)
    {
        if (!in_array('cost', session(self::SESSION_KEY_FIELD_NAME))
            && array_search('cost', $columnTable) !== false) {
            unset($columnTable[array_search('cost', $columnTable)]);
        }
        if (!in_array('clicks', session(self::SESSION_KEY_FIELD_NAME))
            && array_search('clicks', $columnTable) !== false) {
            unset($columnTable[array_search('clicks', $columnTable)]);
        }
        return $columnTable;
    }
}
