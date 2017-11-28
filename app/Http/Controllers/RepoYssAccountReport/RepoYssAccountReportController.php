<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use App\Export\Native\NativePHPCsvExporter;
use App\Export\Spout\SpoutExcelExporter;
use App\Http\Controllers\AbstractReportController;
use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssPrefectureReportCost;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

use DateTime;
use Exception;
use Auth;

class RepoYssAccountReportController extends AbstractReportController
{
    const TIME_PERIOD_TITLE = 'timePeriodTitle';
    const STATUS_TITLE = 'statusTitle';
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';
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
    const SESSION_KEY_ACCOUNT_STATUS = self::SESSION_KEY_PREFIX . 'accountStatus';
    const SESSION_KEY_TIME_PERIOD_TITLE = self::SESSION_KEY_PREFIX. self::TIME_PERIOD_TITLE;
    const SESSION_KEY_STATUS_TITLE = self::SESSION_KEY_PREFIX . self::STATUS_TITLE;
    const SESSION_KEY_START_DAY = self::SESSION_KEY_PREFIX . self::START_DAY;
    const SESSION_KEY_END_DAY = self::SESSION_KEY_PREFIX . self::END_DAY;
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
        'averagePosition'
    ];

    /**
     * @var \App\Model\RepoYssAccountReportCost
     */
    protected $model;

    /**
     * RepoYssAccountReportController constructor.
     *
     * @param ResponseFactory          $responseFactory
     * @param RepoYssAccountReportCost $model
     */
    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssAccountReportCost $model
    ) {
        parent::__construct($responseFactory, $model);
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        session()->forget(self::SESSION_KEY_ENGINE);
        $defaultColumns = self::DEFAULT_COLUMNS;
        array_unshift($defaultColumns, self::GROUPED_BY_FIELD, self::MEDIA_ID);
        if (!session('accountReport')) {
            $this->initializeSession($defaultColumns);
        }
        session([self::SESSION_KEY_ACCOUNT_ID => null]);
        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === self::PREFECTURE) {
            $this->model = new RepoYssPrefectureReportCost;
        }
        $this->checkoutSessionFieldName();
        // display data on the table with current session of date, status and column
        $dataReports = $this->getDataForTable();

        if (isset($request->page)) {
            $this->updateNumberPage($request->page);
        }
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($dataReports->toArray(), ($this->page - 1) * 20, 20),
            count($dataReports),
            20,
            $this->page,
            ["path" => self::SESSION_KEY_PREFIX_ROUTE."/update-table"]
        );
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        return $this->responseFactory->view(
            'yssAccountReport.index',
            [
                self::KEY_PAGINATION => session(self::SESSION_KEY_PAGINATION),
                self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME), // field names which show on top of table
                self::REPORTS => $results, // data that returned from query
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
                //update column on filter column graph
                self::GRAPH_COLUMN_NAME => session(self::SESSION_KEY_GRAPH_COLUMN_NAME),
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

        if (session(self::SESSION_KEY_GROUPED_BY_FIELD) === self::PREFECTURE) {
            $this->model = new RepoYssPrefectureReportCost;
        }

        if ($request->specificItem === self::PREFECTURE) {
            session()->put([self::SESSION_KEY_GROUPED_BY_FIELD => self::PREFECTURE]);
            $this->model = new RepoYssPrefectureReportCost;
        }

        $reports = $this->getDataForTable();
        if (isset($request->page)) {
            $this->updateNumberPage($request->page);
        }
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($reports->toArray(), ($this->page - 1) * 20, 20),
            count($reports),
            20,
            $this->page,
            ["path" => self::SESSION_KEY_PREFIX_ROUTE."/update-table"]
        );
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $summaryReportLayout = view('layouts.summary_report', [self::SUMMARY_REPORT => $summaryReportData])->render();
        $tableDataLayout = view(
            'layouts.table_data',
            [
                self::REPORTS => $results,
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
        foreach ($data as $value) {
            // if data !== null, display on graph
            // else, display "no data found" image
            if ($value->data !== null) {
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

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToCsv()
    {
        $fieldNames = session()->get(self::SESSION_KEY_FIELD_NAME);
        $fieldNames = $this->model->unsetColumns($fieldNames, [self::MEDIA_ID]);

        /** @var $collection \Illuminate\Database\Eloquent\Collection */
        $collection = $this->getDataForTable();

        $aliases = $this->translateFieldNames($fieldNames);

        $exporter = new NativePHPCsvExporter($collection, $fieldNames, $aliases);
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
        $fieldNames = session()->get(self::SESSION_KEY_FIELD_NAME);
        $fieldNames = $this->model->unsetColumns($fieldNames, [self::MEDIA_ID]);

        /** @var $collection \Illuminate\Database\Eloquent\Collection */
        $collection = $this->getDataForTable();

        $aliases = $this->translateFieldNames($fieldNames);

        $exporter = new SpoutExcelExporter($collection, $fieldNames, $aliases);
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
