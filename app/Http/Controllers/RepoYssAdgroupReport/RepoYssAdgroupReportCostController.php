<?php

namespace App\Http\Controllers\RepoYssAdgroupReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\RepoYssAdgroupReportCost;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

use DateTime;
use Exception;
use StdClass;

class RepoYssAdgroupReportCostController extends Controller
{
    const TIME_PERIOD_TITLE = 'timePeriodTitle';
    const STATUS_TITLE = 'statusTitle';
    const GRAPH_COLUMN_NAME = 'graphColumnName';
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';
    const COLUMN_SORT = 'columnSort';
    const SORT = 'sort';
    const SUMMARY_REPORT = 'summaryReport';
    const SESSION_KEY_PREFIX = 'adgroupReport.';
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
    const SESSION_KEY_PREFIX_ROUTE = '/adgroup-report';
    const SESSION_KEY_GROUPED_BY_FIELD = 'adgroupName';

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';
    const GROUPED_BY_FIELD = 'groupedByField';
    const PREFIX_ROUTE = 'prefixRoute';

    const COLUMNS_FOR_FILTER = 'columnsInModal';

    /** @var \App\Model\RepoYssAdgroupReportCost */
    protected $model;

    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssAdgroupReportCost $model
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
        $accountStatus = "enabled";
        $statusTitle = "enabled";
        $graphColumnName = "clicks";
        $summaryReport = [
            'clicks',
            'impressions',
            'cost',
            'averageCpc',
            'averagePosition'
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

    private function updateSessionGraphColumnName($graphColumnName)
    {
        session()->put(self::SESSION_KEY_GRAPH_COLUMN_NAME, $graphColumnName);
    }

    private function updateSessionFieldNameAndPagination($fieldName, $pagination)
    {
        array_unshift($fieldName, self::SESSION_KEY_GROUPED_BY_FIELD);
        if (!in_array(session(self::SESSION_KEY_COLUMN_SORT), $fieldName)) {
            $positionOfFirstFieldName = 1;
            session()->put(self::SESSION_KEY_COLUMN_SORT, $fieldName[$positionOfFirstFieldName]);
        }
        session()->put([
            self::SESSION_KEY_FIELD_NAME => $fieldName,
            self::SESSION_KEY_PAGINATION => $pagination
        ]);
    }

    private function updateSessionStartDayAndEndDayAndTimePeriodTitle($startDay, $endDay, $timePeriodTitle)
    {
        session()->put([
            self::SESSION_KEY_START_DAY => $startDay,
            self::SESSION_KEY_END_DAY => $endDay,
            self::SESSION_KEY_TIME_PERIOD_TITLE => $timePeriodTitle
        ]);
    }

    private function updateSessionStatus($status)
    {
        session()->put([self::SESSION_KEY_ACCOUNT_STATUS => $status]);
    }

    private function updateSessionStatusTitle($statusTitle)
    {
        session()->put([self::SESSION_KEY_STATUS_TITLE => $statusTitle]);
    }

    private function updateSessionColumnSortAndSort($columnSort)
    {
        if (session(self::SESSION_KEY_COLUMN_SORT) !== $columnSort
            || session(self::SESSION_KEY_SORT) !== 'desc') {
            session()->put([
                self::SESSION_KEY_COLUMN_SORT => $columnSort,
                self::SESSION_KEY_SORT => 'desc'
            ]);
        } elseif (session(self::SESSION_KEY_SORT) !== 'asc') {
            session()->put([
                self::SESSION_KEY_COLUMN_SORT => $columnSort,
                self::SESSION_KEY_SORT => 'asc'
            ]);
        }
    }

    private function updateSessionData(Request $request)
    {
        // update session.graphColumnName
        if ($request->graphColumnName !== null) {
            $this->updateSessionGraphColumnName($request->graphColumnName);
        }

        // get fieldName and pagination if available
        if ($request->fieldName !== null && $request->pagination !== null) {
            $this->updateSessionFieldNameAndPagination($request->fieldName, $request->pagination);
        }

        // get startDay and endDay if available
        if ($request->startDay !== null && $request->endDay !== null && $request->timePeriodTitle !== null) {
            $this->updateSessionStartDayAndEndDayAndTimePeriodTitle(
                $request->startDay,
                $request->endDay,
                $request->timePeriodTitle
            );
        }

        // get status if available
        if ($request->status === "all") {
            $this->updateSessionStatus('all');
        } elseif ($request->status !== null) {
            $this->updateSessionStatus($request->status);
        }

        // get statusTitle if available
        if ($request->statusTitle === "all") {
            $this->updateSessionStatusTitle('all');
        } elseif ($request->statusTitle !== null) {
            $this->updateSessionStatusTitle($request->statusTitle);
        }

        //get column sort and sort by if available
        if ($request->columnSort !== null) {
            $this->updateSessionColumnSortAndSort($request->columnSort);
        }
    }

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

    private function getCalculatedSummaryReport()
    {
        return $this->model->calculateSummaryData(
            session(self::SESSION_KEY_SUMMARY_REPORT),
            session(self::SESSION_KEY_ACCOUNT_STATUS),
            session(self::SESSION_KEY_START_DAY),
            session(self::SESSION_KEY_END_DAY)
        );
    }

    private function getCalculatedData()
    {
        return $this->model->calculateData(
            session(self::SESSION_KEY_FIELD_NAME),
            session(self::SESSION_KEY_ACCOUNT_STATUS),
            session(self::SESSION_KEY_START_DAY),
            session(self::SESSION_KEY_END_DAY)
        );
    }

    public function index()
    {
        $allColumns = $this->model->getColumnNames();
        $impossibleColumnsDisplay = [
            'exeDate',
            'startDate',
            'endDate',
            'account_id',
            'campaign_id',
            'campaignID',
            'adgroupID',
            'campaignName',
            'adgroupName',
            'adgroupDistributionSettings',
            'trackingURL',
            'customParameters',
            'network',
            'device',
            'day',
            'dayOfWeek',
            'quarter',
            'month',
            'week',
            'hourofday',
        ];
        $availableColumns = $this->model->unsetColumns($allColumns, $impossibleColumnsDisplay);
        $modalAndSearchColumnsArray = $availableColumns;
        array_unshift($availableColumns, self::SESSION_KEY_GROUPED_BY_FIELD);
        if (!session('adgroupReport')) {
            $this->initializeSession($availableColumns);
        }
        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
        $summaryReportData = $this->getCalculatedSummaryReport();
        return view('yssAdgroupReport.index', [
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
                self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
                self::GROUPED_BY_FIELD => self::SESSION_KEY_GROUPED_BY_FIELD,
        ]);
    }

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

    public function updateTable(Request $request)
    {
        $columns = $this->model->getColumnNames();
        if (!session('adgroupReport')) {
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
            self::PREFIX_ROUTE => self::SESSION_KEY_PREFIX_ROUTE,
            self::GROUPED_BY_FIELD => self::SESSION_KEY_GROUPED_BY_FIELD,
        ])->render();
        return $this->responseFactory->json([
            'summaryReportLayout' => $summaryReportLayout,
            'tableDataLayout' => $tableDataLayout,
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
