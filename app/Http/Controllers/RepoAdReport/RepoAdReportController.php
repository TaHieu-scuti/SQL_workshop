<?php

namespace App\Http\Controllers\RepoAdReport;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\RepoYssPrefectureReportCost;
use App\Http\Controllers\AbstractReportController;
use App\Model\RepoAdwAdReportCost;
use App\Model\RepoYdnAdReport;

use Exception;

class RepoAdReportController extends AbstractReportController
{
    const GRAPH_COLUMN_NAME = 'graphColumnName';
    const COLUMN_SORT = 'columnSort';
    const SORT = 'sort';
    const MEDIA_ID = 'adID';
    const SUMMARY_REPORT = 'summaryReport';
    const SESSION_KEY_PREFIX = 'adReport.';
    const SESSION_KEY_FIELD_NAME = self::SESSION_KEY_PREFIX . 'fieldName';
    const SESSION_KEY_PAGINATION = self::SESSION_KEY_PREFIX . 'pagination';
    const SESSION_KEY_GRAPH_COLUMN_NAME = self::SESSION_KEY_PREFIX . self::GRAPH_COLUMN_NAME;
    const SESSION_KEY_COLUMN_SORT = self::SESSION_KEY_PREFIX . self::COLUMN_SORT;
    const SESSION_KEY_SORT = self::SESSION_KEY_PREFIX . self::SORT;
    const SESSION_KEY_SUMMARY_REPORT = self::SESSION_KEY_PREFIX . self::SUMMARY_REPORT;
    const SESSION_KEY_PREFIX_ROUTE = '/ad-report';
    const SESSION_KEY_GROUPED_BY_FIELD = self::SESSION_KEY_PREFIX . 'groupedByField';

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';
    const GROUPED_BY_FIELD = 'adName';
    const ADW_GROUPED_BY_FIELD = 'ad';
    const PREFIX_ROUTE = 'prefixRoute';
    const SESSION_KEY_OLD_ENGINE = self::SESSION_KEY_PREFIX . 'oldEngine';

    const COLUMNS_FOR_FILTER = 'columnsInModal';
    const DEFAULT_COLUMNS = [
        'impressions',
        'clicks',
        'cost',
        'ctr',
        'averageCpc',
        'averagePosition',
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
     * @var \App\Model\RepoYssAdReportCost
     */
    protected $model;

    public function __construct(
        ResponseFactory $responseFactory,
        RepoYdnAdReport $model
    ) {
        $this->middleware('engine');
        $this->middleware('PreventAccessingWhenChoosingYssEngine');
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    public function index()
    {
        $engine = $this->updateModel();
        $defaultColumns = self::DEFAULT_COLUMNS;
        if ($engine === 'yss' || $engine === 'ydn') {
            array_unshift($defaultColumns, self::MEDIA_ID, self::GROUPED_BY_FIELD, 'adType');
        } elseif ($engine === 'adw') {
            array_unshift($defaultColumns, self::MEDIA_ID, self::ADW_GROUPED_BY_FIELD, 'adType');
        }
        if (!session('adReport')) {
            $this->initializeSession($defaultColumns);
        }
        if ($this->checkoutConditionOfAdgroupForUpdateColumn($engine)) {
            $this->updateGroupByFieldWhenSessionEngineChange($defaultColumns);
        }
        $this->checkoutSessionFieldName();
        return $this->responseFactory->view(
            'adReport.index',
            [
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                self::COLUMNS_FOR_LIVE_SEARCH => self::DEFAULT_COLUMNS_GRAPH,
                self::GRAPH_COLUMN_NAME => session(self::SESSION_KEY_GRAPH_COLUMN_NAME)
            ]
        );
    }

    public function getDataForLayouts()
    {
        $engine = $this->updateModel();
        $this->updateSpecificModel();

        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        //add more columns higher layer to fieldnames
        $columns = $this->getAttributeFieldNames($dataReports);
        $defaultColumns = self::DEFAULT_COLUMNS;
        if ($engine == 'ydn') {
            $defaultColumns = $this->model->unsetColumns($defaultColumns, ['impressionShare']);
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
                self::FIELD_NAMES => $columns,
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TOTAL_DATA_ARRAY => $totalDataArray,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
                'isObjectStdClass' => $this->isObjectStdClass,
            ]
        )->render();
        $fieldsOnModal = view(
            'layouts.fields_on_modal',
            [
                self::COLUMNS_FOR_FILTER => $defaultColumns,
                self::FIELD_NAMES => self::DEFAULT_COLUMNS
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
        $this->updateSessionData($request);
        $this->updateSpecificModel();

        $reports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $summaryReportLayout = view('layouts.summary_report', [self::SUMMARY_REPORT => $summaryReportData])->render();
        //add more columns higher layer to fieldnames
        $columns = $this->getAttributeFieldNames($reports);
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
                'isObjectStdClass' => $this->isObjectStdClass,
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

    protected function updateModel()
    {
        $engine = session(self::SESSION_KEY_ENGINE);
        if ($engine === 'adw') {
            $this->model = new RepoAdwAdReportCost;
        } elseif ($engine === 'ydn') {
            $this->model = new RepoYdnAdReport;
        }
        return $engine;
    }

    private function checkoutConditionOfAdgroupForUpdateColumn($engine)
    {
        if (session(self::SESSION_KEY_OLD_ENGINE) === $engine) {
            if (session(self::SESSION_KEY_OLD_ADGROUP_ID) === session(self::SESSION_KEY_AD_GROUP_ID)
                && self::SESSION_KEY_PREFIX === session(self::SESSION_KEY_PREVIOUS_PREFIX)
            ) {
                return false; // same campaign => no update
            }
            return true; // same engine, different campaignId => update back to normal report
        }
        return true;
    }
}
