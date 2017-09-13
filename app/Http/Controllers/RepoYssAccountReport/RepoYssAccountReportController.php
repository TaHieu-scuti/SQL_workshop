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
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';
    const COLUMN_SORT = 'columnSort';
    const ACCOUNT_ID = 'account_id';
    const ACCOUNT_NAME = 'accountName';
    const SORT = 'sort';
    const SESSION_KEY_PREFIX = 'accountReport.';
    const SESSION_KEY_FIELD_NAME = self::SESSION_KEY_PREFIX . 'fieldName';
    const SESSION_KEY_ACCOUNT_STATUS = self::SESSION_KEY_PREFIX . 'accountStatus';
    const SESSION_KEY_TIME_PERIOD_TITLE = self::SESSION_KEY_PREFIX. self::TIME_PERIOD_TITLE;
    const SESSION_KEY_STATUS_TITLE = self::SESSION_KEY_PREFIX . 'statusTitle';
    const SESSION_KEY_START_DAY = self::SESSION_KEY_PREFIX . self::START_DAY;
    const SESSION_KEY_END_DAY = self::SESSION_KEY_PREFIX . self::END_DAY;
    const SESSION_KEY_PAGINATION = self::SESSION_KEY_PREFIX . 'pagination';
    const SESSION_KEY_GRAPH_COLUMN_NAME = self::SESSION_KEY_PREFIX . 'graphColumnName';
    const SESSION_KEY_COLUMN_SORT = self::SESSION_KEY_PREFIX . self::COLUMN_SORT;
    const SESSION_KEY_SORT = self::SESSION_KEY_PREFIX . self::SORT;

    const REPORTS = 'reports';
    const FIELD_NAMES = 'fieldNames';
    const TOTAL_DATA_ARRAY = 'totalDataArray';
    const COLUMNS = 'columns';
    const COLUMNS_FOR_LIVE_SEARCH = 'columnsLiveSearch';
    const KEY_PAGINATION = 'keyPagination';
    const DEFAULT_ACCOUNT_STATUS = 'enabled';

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
        // dd(session(self::SESSION_KEY_COLUMN_SORT));
        return $this->model->testDataForTable(
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

    /**
     * @param string[] $columns
     */
    private function initializeSession(array $columns)
    {
        $today = new DateTime;
        $endDay = $today->format('Y-m-d');
        $startDay = $today->modify('-90 days')->format('Y-m-d');
        $timePeriodTitle = "Last 90 days";
        session([self::SESSION_KEY_FIELD_NAME => $columns]);
        session([self::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_ACCOUNT_STATUS]);
        session([self::SESSION_KEY_TIME_PERIOD_TITLE => $timePeriodTitle]);
        session([self::SESSION_KEY_START_DAY => $startDay]);
        session([self::SESSION_KEY_END_DAY => $endDay]);
        session([self::SESSION_KEY_PAGINATION => 20]);
        session([self::SESSION_KEY_COLUMN_SORT => 'impressions']);
        session([self::SESSION_KEY_SORT => 'desc']);
    }

    /**
     * @param Request $request
     */
    private function updateSessionData(Request $request)
    {
        // get fieldName and pagination if available
        if ($request->pagination !== null) {
            session()->put(self::SESSION_KEY_PAGINATION, $request->pagination);
        }
        if ($request->fieldName !== null) {
            $fieldName = $request->fieldName;
            session()->put([
                self::SESSION_KEY_FIELD_NAME => $fieldName,
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
        if ($request->status !== null) {
            session()->put([self::SESSION_KEY_ACCOUNT_STATUS => $request->status]);
        } else {
            session()->put([self::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_ACCOUNT_STATUS]);
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
    public function index2()
    {
        $columns = $this->model->getColumnNames();
        $columnsInModal = $this->model->unsetColumns($columns, ['account_id']);
        //get data column live search
        // unset day, day of week....
        $unsetColumns = ['network', 'device', 'day', 'dayOfWeek', 'week', 'month', 'quarter'];
        $columnsLiveSearch = $this->model->unsetColumns($columns, $unsetColumns);

        // initialize session for table with fieldName,
        // status, start and end date, pagination
        if (!session('accountReport')) {
            $this->initializeSession($columns);
        }

        // display data on the table with current session of date, status and column
        $dataReports = $this->getDataForTable();

        $totalDataArray = $this->getCalculatedData();
        return $this->responseFactory->view(
            'yssAccountReport.index',
            [
                self::KEY_PAGINATION => session(self::SESSION_KEY_PAGINATION),
                self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME), // field names which show on top of table
                self::REPORTS => $dataReports, // data that returned from query
                self::COLUMNS => $columns, // all columns that show up in modal
                self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
                self::SORT => session(self::SESSION_KEY_SORT),
                self::TIME_PERIOD_TITLE => session(self::SESSION_KEY_TIME_PERIOD_TITLE),
                self::START_DAY => session(self::SESSION_KEY_START_DAY),
                self::END_DAY => session(self::SESSION_KEY_END_DAY),
                self::COLUMNS_FOR_LIVE_SEARCH => $columnsLiveSearch, // all columns that show columns live search
                self::TOTAL_DATA_ARRAY => $totalDataArray, // total data of each field
                self::COLUMNS_FOR_FILTER => $columnsInModal,
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
        return $this->responseFactory->json(view('layouts.table_data', [
            self::REPORTS => $reports,
            self::FIELD_NAMES => session(self::SESSION_KEY_FIELD_NAME),
            self::COLUMN_SORT => session(self::SESSION_KEY_COLUMN_SORT),
            self::SORT => session(self::SESSION_KEY_SORT),
            self::TOTAL_DATA_ARRAY => $totalDataArray
        ])->render());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function displayGraph(Request $request)
    {
        // update session.graphColumnName
        if ($request->graphColumnName !== null) {
            session()->put(self::SESSION_KEY_GRAPH_COLUMN_NAME, $request->graphColumnName);
        } elseif (!session(self::SESSION_KEY_GRAPH_COLUMN_NAME)) {
            // if get no column name, set selected column click
            session()->put(self::SESSION_KEY_GRAPH_COLUMN_NAME, 'clicks');
        }

        // get startDay and endDay if available
        if ($request->startDay !== null && $request->endDay !== null && $request->timePeriodTitle !== null) {
            session()->put([
                    self::SESSION_KEY_START_DAY => $request->startDay,
                    self::SESSION_KEY_END_DAY => $request->endDay,
                    self::SESSION_KEY_TIME_PERIOD_TITLE => $request->timePeriodTitle,
            ]);
        }

        // get status if available
        if ($request->status !== null) {
            session()->put([self::SESSION_KEY_ACCOUNT_STATUS => $request->status]);
        }

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

        return $this->responseFactory->json([
                        'data' => $data,
                        'field' => session(self::SESSION_KEY_GRAPH_COLUMN_NAME),
                        'timePeriodLayout' => $timePeriodLayout
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

    public function index()
    {
        $allColumns = $this->model->getColumnNames();
        $unpossibleColumnsDisplay = ['account_id', 'ctr', 'averagePosition', 'trackingURL', 'network', 'device', 'day', 'dayOfWeek', 'week', 'month', 'quarter'];
        $availableColumns = $this->model->unsetColumns($allColumns, $unpossibleColumnsDisplay);
        if (!session('accountReport')) {
            $this->initializeSession($availableColumns);
        }
        $dataReports = $this->getDataForTable();
        $totalDataArray = $this->getCalculatedData();
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
                self::START_DAY => session(self::SESSION_KEY_START_DAY),
                self::END_DAY => session(self::SESSION_KEY_END_DAY),
                self::COLUMNS_FOR_LIVE_SEARCH => $availableColumns, // all columns that show columns live search
                self::TOTAL_DATA_ARRAY => $totalDataArray, // total data of each field
                self::COLUMNS_FOR_FILTER => $availableColumns,
            ]
        );
    }
}
