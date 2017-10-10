<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use App\Http\Controllers\AbstractReportController;
use App\RepoYssAccountReport;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

use DateTime;
use Exception;
use StdClass;

class RepoYssAccountReportController extends AbstractReportController
{
    const TIME_PERIOD_TITLE = 'timePeriodTitle';
    const STATUS_TITLE = 'statusTitle';
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';
    const COLUMN_SORT = 'columnSort';
    const ACCOUNT_ID = 'account_id';
    const GROUPED_BY_FIELD = 'accountName';
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

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';
    const PREFIX_ROUTE = '/account_report';

    const COLUMNS_FOR_FILTER = 'columnsInModal';

    /** @var \App\RepoYssAccountReport */
    protected $model;

    /**
     * RepoYssAccountReportController constructor.
     * @param ResponseFactory      $responseFactory
     * @param RepoYssAccountReport $model
     */
    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssAccountReport $model
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

    /**
     * @return array|\Illuminate\Support\Collection
     */
    private function getDataForGraph()
    {
        $data = $this->model->getDataForGraph(
            session(self::SESSION_KEY_GRAPH_COLUMN_NAME),
            session(self::SESSION_KEY_ACCOUNT_STATUS),
            session(self::SESSION_KEY_START_DAY),
            session(self::SESSION_KEY_END_DAY)
        );

        if ($data->isEmpty()) {
            if (session(self::SESSION_KEY_END_DAY) === session(self::SESSION_KEY_START_DAY)) {
                $data[] = ['day' => session(self::SESSION_KEY_START_DAY), 'data' => 0];
            } else {
                $data[] = ['day' => session(self::SESSION_KEY_END_DAY), 'data' => 0];
                $data[] = ['day' => session(self::SESSION_KEY_START_DAY), 'data' => 0];
            }
        }

        return $data;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getDataForTable()
    {
        return $this->model->getDataForTable(
            session(self::SESSION_KEY_FIELD_NAME),
            session(self::SESSION_KEY_ACCOUNT_STATUS),
            session(self::SESSION_KEY_START_DAY),
            session(self::SESSION_KEY_END_DAY),
            session(self::SESSION_KEY_PAGINATION),
            session(self::SESSION_KEY_COLUMN_SORT),
            session(self::SESSION_KEY_SORT)
        );
    }

    /**
     * @return array
     */
    private function getCalculatedData()
    {
        return $this->model->calculateData(
            session(self::SESSION_KEY_FIELD_NAME),
            session(self::SESSION_KEY_ACCOUNT_STATUS),
            session(self::SESSION_KEY_START_DAY),
            session(self::SESSION_KEY_END_DAY)
        );
    }

    private function getCalculatedSummaryReport()
    {
        return $this->model->calculateSummaryData(
            session(self::SESSION_KEY_SUMMARY_REPORT),
            session(self::SESSION_KEY_ACCOUNT_STATUS),
            session(self::SESSION_KEY_START_DAY),
            session(self::SESSION_KEY_END_DAY)
        );
    }

    /**
     * @param string[] $columns
     */
    private function initializeSession(array $columns)
    {
        $today = new DateTime;
        $endDay = $today->format('Y-m-d');
        $startDay = $today->modify('-90 days')->format('Y-m-d');
        $timePeriodTitle = "Last 90 days";
        $accountStatus = "enabled";
        $statusTitle = "enabled";
        $graphColumnName = "clicks";
        $summaryReport = [
            'clicks',
            'impressions',
            'cost',
            'averageCpc',
            'averagePosition',
            'invalidClicks'
        ];
        session([self::SESSION_KEY_FIELD_NAME => $columns]);
        session([self::SESSION_KEY_ACCOUNT_STATUS => $accountStatus]);
        session([self::SESSION_KEY_TIME_PERIOD_TITLE => $timePeriodTitle]);
        session([self::SESSION_KEY_STATUS_TITLE => $statusTitle]);
        session([self::SESSION_KEY_START_DAY => $startDay]);
        session([self::SESSION_KEY_END_DAY => $endDay]);
        session([self::SESSION_KEY_PAGINATION => 20]);
        session([self::SESSION_KEY_GRAPH_COLUMN_NAME => $graphColumnName]);
        session([self::SESSION_KEY_COLUMN_SORT => 'impressions']);
        session([self::SESSION_KEY_SORT => 'desc']);
        session([self::SESSION_KEY_SUMMARY_REPORT => $summaryReport]);
    }

    /**
     * @param Request $request
     */
    private function updateSessionData(Request $request)
    {
        // update session.graphColumnName
        if ($request->graphColumnName !== null) {
            session()->put(self::SESSION_KEY_GRAPH_COLUMN_NAME, $request->graphColumnName);
        }

        // get fieldName and pagination if available
        if ($request->fieldName !== null && $request->pagination !== null) {
            $fieldName = $request->fieldName;
            array_unshift($fieldName, self::GROUPED_BY_FIELD);
            if (!in_array(session(self::SESSION_KEY_COLUMN_SORT), $fieldName)) {
                $positionOfFirstFieldName = 1;
                session()->put(self::SESSION_KEY_COLUMN_SORT, $fieldName[$positionOfFirstFieldName]);
            }
            session()->put([
                self::SESSION_KEY_FIELD_NAME => $fieldName,
                self::SESSION_KEY_PAGINATION => $request->pagination,
            ]);
        }

        // get startDay and endDay if available
        if ($request->startDay !== null && $request->endDay !== null && $request->timePeriodTitle !== null) {
            session()->put([
                self::SESSION_KEY_START_DAY => $request->startDay,
                self::SESSION_KEY_END_DAY => $request->endDay,
                self::SESSION_KEY_TIME_PERIOD_TITLE => $request->timePeriodTitle
            ]);
        }

        // get status if available
        if ($request->status === "all") {
            session()->put([self::SESSION_KEY_ACCOUNT_STATUS => ""]);
        } elseif ($request->status !== null) {
            session()->put([self::SESSION_KEY_ACCOUNT_STATUS => $request->status]);
        }

        if ($request->statusTitle === "all") {
            session()->put([self::SESSION_KEY_STATUS_TITLE => "all"]);
        } elseif ($request->statusTitle !== null) {
            session()->put([self::SESSION_KEY_STATUS_TITLE => $request->statusTitle]);
        }

        //get column sort and sort by if available
        if ($request->columnSort !== null) {
            if (session(self::SESSION_KEY_COLUMN_SORT) !== $request->columnSort
                || session(self::SESSION_KEY_SORT) !== 'desc') {
                session()->put([
                    self::SESSION_KEY_COLUMN_SORT => $request->columnSort,
                    self::SESSION_KEY_SORT => 'desc'
                ]);
            } elseif (session(self::SESSION_KEY_SORT) !== 'asc') {
                session()->put([
                    self::SESSION_KEY_COLUMN_SORT => $request->columnSort,
                    self::SESSION_KEY_SORT => 'asc'
                ]);
            }
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allColumns = $this->model->getColumnNames();
        $unpossibleColumnsDisplay = [
            'account_id',
            'ctr',
            'averagePosition',
            'trackingURL',
            'network',
            'device',
            'day',
            'dayOfWeek',
            'week',
            'month',
            'quarter'
        ];
        $availableColumns = $this->model->unsetColumns($allColumns, $unpossibleColumnsDisplay);
        $modalAndSearchColumnsArray = $availableColumns;
        array_unshift($availableColumns, 'accountName');
        if (!session('accountReport')) {
            $this->initializeSession($availableColumns);
        }
        // display data on the table with current session of date, status and column
        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        return $this->responseFactory->view(
            'yssAccountReport.index',
            [
                self::KEY_PAGINATION => session(self::SESSION_KEY_PAGINATION),
                self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME), // field names which show on top of table
                self::REPORTS => $dataReports, // data that returned from query
                self::COLUMNS => $availableColumns, // all columns that show up in modal
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TIME_PERIOD_TITLE => session(self::SESSION_KEY_TIME_PERIOD_TITLE),
                self::STATUS_TITLE => session(self::SESSION_KEY_STATUS_TITLE),
                self::START_DAY => session(self::SESSION_KEY_START_DAY),
                self::END_DAY => session(self::SESSION_KEY_END_DAY),
                // all columns that show columns live search
                self::COLUMNS_FOR_LIVE_SEARCH => $modalAndSearchColumnsArray,
                self::TOTAL_DATA_ARRAY => $totalDataArray, // total data of each field
                self::COLUMNS_FOR_FILTER => $modalAndSearchColumnsArray,
                self::SUMMARY_REPORT => $summaryReportData,
                'prefixRoute' => self::PREFIX_ROUTE,
                'groupedByField' => self::GROUPED_BY_FIELD,
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
        $reports = $this->getDataForTable();

        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        $summaryReportLayout = view('layouts.summary_report', [self::SUMMARY_REPORT => $summaryReportData])->render();
        $tableDataLayout = view('layouts.table_data', [
            self::REPORTS => $reports,
            self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME),
            self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
            self::SORT => session(self::SESSION_KEY_SORT),
            self::TOTAL_DATA_ARRAY => $totalDataArray,
            'prefixRoute' => self::PREFIX_ROUTE,
            'groupedByField' => self::GROUPED_BY_FIELD,
        ])->render();
        return $this->responseFactory->json([
            'summaryReportLayout' => $summaryReportLayout,
            'tableDataLayout' => $tableDataLayout,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function displayGraph(Request $request)
    {
        $this->updateSessionData($request);
        try {
            $data = $this->getDataForGraph();
        } catch (Exception $exception) {
            return $this->generateJSONErrorResponse($exception);
        }
        $timePeriodLayout = view('layouts.time-period')
                        ->with(self::START_DAY, session(self::SESSION_KEY_START_DAY))
                        ->with(self::END_DAY, session(self::SESSION_KEY_END_DAY))
                        ->with(self::TIME_PERIOD_TITLE, session(self::SESSION_KEY_TIME_PERIOD_TITLE))
                        ->render();
        $statusLayout = view('layouts.status-title')
                        ->with(self::STATUS_TITLE, session(self::SESSION_KEY_STATUS_TITLE))
                        ->render();
        $graphColumnLayout = view('layouts.graph-column')
                        ->with('graphColumnName', session(self::SESSION_KEY_GRAPH_COLUMN_NAME))
                        ->render();
        return $this->responseFactory->json([
                        'data' => $data,
                        'field' => session(self::SESSION_KEY_GRAPH_COLUMN_NAME),
                        'timePeriodLayout' => $timePeriodLayout,
                        'graphColumnLayout' => $graphColumnLayout,
                        'statusLayout' => $statusLayout,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function liveSearch(Request $request)
    {
        $result = $this->model->getColumnLiveSearch($request["keywords"]);

        return $this->responseFactory->view(
            'layouts.dropdown_search',
            [self::COLUMNS_FOR_LIVE_SEARCH => $result]
        );
    }
}
