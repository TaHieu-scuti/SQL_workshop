<?php
namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use App\Export\Native\NativePHPCsvExporter;
use App\Export\Spout\SpoutExcelExporter;
use App\Http\Controllers\AbstractReportController;
use App\Model\Account;
use DateTime;

use Illuminate\Contracts\Routing\ResponseFactory;

class ClientsController extends AbstractReportController
{
    const COLUMN_SORT = 'columnSort';
    const ACCOUNT_ID = 'account_id';
    const MEDIA_ID = 'accountid';
    const GROUPED_BY_FIELD = 'accountName';
    const PREFIX_ROUTE = 'prefixRoute';
    const SORT = 'sort';
    const GRAPH_COLUMN_NAME = "graphColumnName";
    const SUMMARY_REPORT = "summaryReport";
    const SESSION_KEY_PREFIX = 'client.';
    const SESSION_KEY_FIELD_NAME = self::SESSION_KEY_PREFIX . 'fieldName';
    const SESSION_KEY_PAGINATION = self::SESSION_KEY_PREFIX . 'pagination';
    const SESSION_KEY_GRAPH_COLUMN_NAME = self::SESSION_KEY_PREFIX . self::GRAPH_COLUMN_NAME;
    const SESSION_KEY_COLUMN_SORT = self::SESSION_KEY_PREFIX . self::COLUMN_SORT;
    const SESSION_KEY_SORT = self::SESSION_KEY_PREFIX . self::SORT;
    const SESSION_KEY_SUMMARY_REPORT = self::SESSION_KEY_PREFIX . self::SUMMARY_REPORT;
    const SESSION_KEY_PREFIX_ROUTE = '/client-report';
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
        'cost',
        'clicks',
        'ctr',
        'averageCpc',
        'averagePosition',
        'ydn_web_cv',
        'ydn_web_cvr',
        'ydn_web_cpa',
        'yss_web_cv',
        'yss_web_cvr',
        'yss_web_cpa',
        'adw_web_cv',
        'adw_web_cvr',
        'adw_web_cpa',
        'web_cv',
        'web_cvr',
        'web_cpa',
        'ydn_call_cv',
        'ydn_call_cvr',
        'ydn_call_cpa',
        'yss_call_cv',
        'yss_call_cvr',
        'yss_call_cpa',
        'adw_call_cv',
        'adw_call_cvr',
        'adw_call_cpa',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
    ];

    /**
     * @var \App\Model\RepoYssAccountReportCost
     */
    protected $model;

    /**
     * RepoYssAccountReportController constructor.
     *
     * @param ResponseFactory          $responseFactory
     * @param Account $model
     */
    public function __construct(
        ResponseFactory $responseFactory,
        Account $model
    ) {
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session()->forget(self::SESSION_KEY_ENGINE);
        session()->put([self::SESSION_KEY_CLIENT_ID => null]);
        $defaultColumns = self::DEFAULT_COLUMNS;
        array_unshift($defaultColumns, self::GROUPED_BY_FIELD, self::ACCOUNT_ID);

        if (!session('client')) {
            $this->initializeSession($defaultColumns);
        }
        if (!session('accountStatus')) {
            $this->initializeStatusSession();
        }
        if (!session('timePeriodTitle')) {
            $this->initializeTimeRangeSession();
        }

        session([self::SESSION_KEY_ACCOUNT_ID => null]);

        return $this->responseFactory->view(
            'clients.index',
            [
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE
            ]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getDataForLayouts(Request $request)
    {
        $dataReports = $this->getDataForTable();
        if (isset($request->page)) {
            $this->updateNumberPage($request->page);
        }
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($dataReports, ($this->page - 1) * 20, 20),
            count($dataReports),
            20,
            $this->page,
            ["path" => self::SESSION_KEY_PREFIX_ROUTE."/update-table"]
        );

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
                self::REPORTS => $results,
                self::FIELD_NAMES => $this->updateColumnAccountNameToClientNameOrAgencyName(
                    session(self::SESSION_KEY_FIELD_NAME),
                    self::SESSION_KEY_PREFIX_ROUTE
                ),
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
                self::FIELD_NAMES => $this->updateColumnAccountNameToClientNameOrAgencyName(
                    session(self::SESSION_KEY_FIELD_NAME),
                    self::SESSION_KEY_PREFIX_ROUTE
                )
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTable(Request $request)
    {
        $columns = $this->model->getColumnNames();
        if (!session('client')) {
            $this->initializeSession($columns);
        }

        $this->updateSessionData($request);

        $reports = $this->getDataForTable();
        if (isset($request->page)) {
            $this->updateNumberPage($request->page);
        }
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($reports, ($this->page - 1) * 20, 20),
            count($reports),
            20,
            $this->page,
            ["path" => self::SESSION_KEY_PREFIX_ROUTE."/update-table"]
        );
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
                self::REPORTS => $results,
                self::FIELD_NAMES => $this->updateColumnAccountNameToClientNameOrAgencyName(
                    session(self::SESSION_KEY_FIELD_NAME),
                    self::SESSION_KEY_PREFIX_ROUTE
                ),
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TOTAL_DATA_ARRAY => $totalDataArray,
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                'groupedByField' => session(self::SESSION_KEY_GROUPED_BY_FIELD),
            ]
        )->render();
        // if no data found
        // display no data found message on table
        if ($results->total() !== 0) {
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function displayGraph(Request $request)
    {
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
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                // if data !== null, display on graph
                // else, display "no data found" image
                if (isset($value->data)) {
                    $this->displayNoDataFoundMessageOnGraph = false;
                }
            }
        }

        return $this->responseFactory->json(
            [
                'data' => $data,
                'field' => session(self::SESSION_KEY_GRAPH_COLUMN_NAME),
                'timePeriodLayout' => $timePeriodLayout,
                'statusLayout' => $statusLayout,
                'displayNoDataFoundMessageOnGraph' => $this->displayNoDataFoundMessageOnGraph,
            ]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToCsv()
    {
        $fieldNames = $this->updateColumnAccountNameToClientNameOrAgencyName(
            session()->get(self::SESSION_KEY_FIELD_NAME),
            self::SESSION_KEY_PREFIX_ROUTE
        );
        $fieldNames = $this->model->unsetColumns($fieldNames, [self::MEDIA_ID]);
        /** @var $collection \Illuminate\Database\Eloquent\Collection */
        $collection = $this->getDataForTable();
        $aliases = $this->translateFieldNames($fieldNames);
        $exporter = new NativePHPCsvExporter(collect($collection), $fieldNames, $aliases);
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
        $fieldNames = $this->updateColumnAccountNameToClientNameOrAgencyName(
            session()->get(self::SESSION_KEY_FIELD_NAME),
            self::SESSION_KEY_PREFIX_ROUTE
        );
        $fieldNames = $this->model->unsetColumns($fieldNames, [self::MEDIA_ID]);
        /** @var $collection \Illuminate\Database\Eloquent\Collection */
        $collection = $this->getDataForTable();

        $aliases = $this->translateFieldNames($fieldNames);

        $exporter = new SpoutExcelExporter(collect($collection), $fieldNames, $aliases);
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
}
