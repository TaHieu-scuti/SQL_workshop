<?php

namespace App\Http\Controllers\RepoAccountReport;

use App\Export\Native\NativePHPCsvExporter;
use App\Export\Spout\SpoutExcelExporter;
use App\Http\Controllers\AbstractReportController;
use App\Model\RepoYssAccountReportCost;
use App\Model\RepoAccountPrefecture;
use App\Model\RepoAccountTimezone;
use App\Model\RepoAccountDayOfWeek;
use App\Model\RepoAccountDevice;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

use DateTime;
use Exception;
use Auth;
use Illuminate\Support\Facades\Lang;

class RepoAccountReportController extends AbstractReportController
{
    const COLUMN_SORT = 'columnSort';
    const ACCOUNT_ID = 'account_id';
    const MEDIA_ID = 'accountid';
    const GROUPED_BY_FIELD = 'accountName';
    const PREFIX_ROUTE = 'prefixRoute';
    const SORT = 'sort';
    const GRAPH_COLUMN_NAME = "graphColumnName";
    const SUMMARY_REPORT = "summaryReport";
    const SESSION_KEY_PREFIX = 'accountReport.';
    const SESSION_KEY_FIELD_NAME = self::SESSION_KEY_PREFIX . 'fieldName';
    const SESSION_KEY_PAGINATION = self::SESSION_KEY_PREFIX . 'pagination';
    const SESSION_KEY_GRAPH_COLUMN_NAME = self::SESSION_KEY_PREFIX . self::GRAPH_COLUMN_NAME;
    const SESSION_KEY_COLUMN_SORT = self::SESSION_KEY_PREFIX . self::COLUMN_SORT;
    const SESSION_KEY_SORT = self::SESSION_KEY_PREFIX . self::SORT;
    const SESSION_KEY_SUMMARY_REPORT = self::SESSION_KEY_PREFIX . self::SUMMARY_REPORT;
    const SESSION_KEY_PREFIX_ROUTE = '/account_report';
    const SESSION_KEY_GROUPED_BY_FIELD = self::SESSION_KEY_PREFIX . 'groupedByField';

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';
    const PREFECTURE = 'prefecture';

    const COLUMNS_FOR_FILTER = 'columnsInModal';
    const DEFAULT_COLUMNS = [
        'impressions',
        'clicks',
        'cost',
        'ctr',
        'averageCpc',
        'averagePosition',
        'dailySpendingLimit',
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

    const SESSION_NEED_FIELDS = [
        'dayOfWeek' => [
            'need' => ['dayOfWeek'],
            'no_need' => ['accountName', 'accountid'],
            'not_display' => ['dailySpendingLimit']
        ],
        'accountName' => [
            'need' => ['accountName', 'accountid'],
            'no_need' => ['dayOfWeek', 'prefecture', 'hourofday', 'device'],
            'not_display' => []
        ],
        'hourofday' => [
            'need' => ['hourofday'],
            'no_need' => ['accountName', 'accountid'],
            'not_display' => ['dailySpendingLimit']
        ],
        'prefecture' => [
            'need' => ['prefecture'],
            'no_need' => ['accountName', 'accountid'],
            'not_display' => ['dailySpendingLimit']
        ],
        'device' => [
            'need' => ['device'],
            'no_need' => ['accountName', 'accountid'],
            'not_display' => ['dailySpendingLimit']
        ]
    ];

    /**
     * @var \App\Model\RepoYssAccountReportCost
     */
    protected $model;

    /**
     * RepoAccountReportController constructor.
     *
     * @param ResponseFactory          $responseFactory
     * @param RepoYssAccountReportCost $model
     */
    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssAccountReportCost $model
    ) {
        $this->middleware('checkRoleClient');
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session()->put([self::SESSION_KEY_OLD_ENGINE => session(self::SESSION_KEY_ENGINE)]);
        session()->forget(self::SESSION_KEY_ENGINE);
        $defaultColumns = self::DEFAULT_COLUMNS;
        array_unshift($defaultColumns, self::GROUPED_BY_FIELD, self::MEDIA_ID);
        
        if (!session('accountReport')) {
            $this->initializeSession($defaultColumns);
        }
        if (!session('accountStatus')) {
            $this->initializeStatusSession();
        }
        if (!session('timePeriodTitle')) {
            $this->initializeTimeRangeSession();
        }

        session([self::SESSION_KEY_ACCOUNT_ID => null]);

        $this->checkoutSessionFieldName();
        return $this->responseFactory->view(
            'accountReport.index',
            [
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                self::COLUMNS_FOR_LIVE_SEARCH => self::DEFAULT_COLUMNS_GRAPH,
                self::GRAPH_COLUMN_NAME => session(self::SESSION_KEY_GRAPH_COLUMN_NAME)
            ]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getDataForLayouts(Request $request)
    {
        $this->updateModel();

        $dataReports = $this->getDataForTable();
        if (isset($request->page)) {
            $this->updateNumberPage($request->page);
        }
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
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
                'isObjectStdClass' => $this->isObjectStdClass,
                self::FIELD_NAMES => $this->getColumnDisplay(),
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TOTAL_DATA_ARRAY => $totalDataArray,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
            ]
        )->render();
        $defaultColumns = array_diff(
            self::DEFAULT_COLUMNS,
            self::SESSION_NEED_FIELDS[session(self::SESSION_KEY_GROUPED_BY_FIELD)]['not_display']
        );
        $fieldsOnModal = view(
            'layouts.fields_on_modal',
            [
                self::COLUMNS_FOR_FILTER => $defaultColumns,
                self::FIELD_NAMES => $this->getColumnDisplay()
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTable(Request $request)
    {
        $columns = $this->model->getColumnNames();
        if (!session('accountReport')) {
            $this->initializeSession($columns);
        }
        $this->updateSessionData($request);

        $this->updateModel();

        $this->handlerSession();

        $reports = $this->getDataForTable();
        if (isset($request->page)) {
            $this->updateNumberPage($request->page);
        }
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $summaryReportLayout = view('layouts.summary_report', [self::SUMMARY_REPORT => $summaryReportData])->render();
        $defaultColumns = array_diff(
            self::DEFAULT_COLUMNS,
            self::SESSION_NEED_FIELDS[session(self::SESSION_KEY_GROUPED_BY_FIELD)]['not_display']
        );
        $fieldsOnModal = view(
            'layouts.fields_on_modal',
            [
                self::COLUMNS_FOR_FILTER => $defaultColumns,
                self::FIELD_NAMES => $this->getColumnDisplay()
            ]
        )->render();
        $tableDataLayout = view(
            'layouts.table_data',
            [
                self::REPORTS => $reports,
                'isObjectStdClass' => $this->isObjectStdClass,
                self::FIELD_NAMES => $this->getColumnDisplay(),
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
                'isLoadFilterColumn' => $fieldsOnModal,
                'displayNoDataFoundMessageOnTable' => $this->displayNoDataFoundMessageOnTable
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function displayGraph(Request $request)
    {
        $this->updateSessionData($request);
        $column = Lang::get('language.'.str_slug(session(self::SESSION_KEY_GRAPH_COLUMN_NAME)));
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
            if (isset($value->data)) {
                $this->displayNoDataFoundMessageOnGraph = false;
            }
        }
        return $this->responseFactory->json(
            [
                'data' => $data,
                'field' => Lang::get('language.'.session(self::SESSION_KEY_GRAPH_COLUMN_NAME)),
                'timePeriodLayout' => $timePeriodLayout,
                'statusLayout' => $statusLayout,
                'displayNoDataFoundMessageOnGraph' => $this->displayNoDataFoundMessageOnGraph,
                'column' => $column,
                'status' => session(static::SESSION_KEY_ACCOUNT_STATUS)
            ]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToCsv()
    {
        $this->updateModel();
        /** @var $collection Array data get from table */
        $collection = $this->getDataForTable()->items();
        $fieldNames = $this->getFieldNamesForExport($collection);
        $aliases = $this->translateFieldNames($fieldNames);
        $reportType = str_replace('/', '', static::SESSION_KEY_PREFIX_ROUTE);
        $exporter = new NativePHPCsvExporter(collect($collection), $reportType, $fieldNames, $aliases);
        $csvData = $exporter->export();

        return $this->responseFactory->make(
            $csvData,
            200,
            [
                'Content-Type' => 'application/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $exporter->getFileName() . '"',
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                'Last-Modified' => (new DateTime)->format('D, d M Y H:i:s'),
                'Cache-Control' => 'cache, must-revalidate, private',
                'Pragma' => 'public'
            ]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToExcel()
    {
        $this->updateModel();
        /** @var $collection Array data get from table */
        $collection = $this->getDataForTable()->items();
        $fieldNames = $this->getFieldNamesForExport($collection);
        $aliases = $this->translateFieldNames($fieldNames);
        $reportType = str_replace('/', '', static::SESSION_KEY_PREFIX_ROUTE);
        $exporter = new SpoutExcelExporter(collect($collection), $reportType, $fieldNames, $aliases);
        $excelData = $exporter->export();

        return $this->responseFactory->make(
            $excelData,
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $exporter->getFileName() . '"',
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                'Last-Modified' => (new DateTime)->format('D, d M Y H:i:s'),
                'Cache-Control' => 'cache, must-revalidate, private',
                'Pragma' => 'public'
            ]
        );
    }

    private function handlerSession()
    {
        $sessionFieldNeeds = array_diff(
            session(self::SESSION_KEY_FIELD_NAME),
            self::SESSION_NEED_FIELDS[session(self::SESSION_KEY_GROUPED_BY_FIELD)]['no_need']
        );
        foreach (self::SESSION_NEED_FIELDS[session(self::SESSION_KEY_GROUPED_BY_FIELD)]['need'] as $value) {
            array_unshift($sessionFieldNeeds, $value);
        }
        session()->put([self::SESSION_KEY_FIELD_NAME => array_unique($sessionFieldNeeds)]);
    }

    private function getColumnDisplay()
    {
        return array_diff(
            session(self::SESSION_KEY_FIELD_NAME),
            self::SESSION_NEED_FIELDS[session(self::SESSION_KEY_GROUPED_BY_FIELD)]['not_display']
        );
    }

    private function updateModel()
    {
        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === self::PREFECTURE) {
            $this->model = new RepoAccountPrefecture;
        }

        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === 'hourofday') {
            $this->model = new RepoAccountTimezone;
        }

        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === 'dayOfWeek') {
            $this->model = new RepoAccountDayOfWeek;
        }

        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === 'device') {
            $this->model = new RepoAccountDevice;
        }
    }
}
