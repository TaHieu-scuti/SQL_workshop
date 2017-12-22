<?php

namespace App\Http\Controllers;

use App\AbstractReportModel;
use App\Export\Native\NativePHPCsvExporter;
use App\Export\Spout\SpoutExcelExporter;
use Illuminate\Http\Request;
use App\Model\RepoAdwGeoReportCost;
use App\Model\RepoYdnPrefecture;
use App\Model\RepoYdnTimezone;
use App\Model\RepoYssPrefectureReportCost;
use App\Model\RepoAdwSearchQueryPerformanceReport;
use App\Model\RepoYssSearchqueryReportCost;

use Illuminate\Contracts\Routing\ResponseFactory;

use DateTime;
use Exception;
use StdClass;
use Auth;

abstract class AbstractReportController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var \App\AbstractReportModel
     */
    protected $model;
    const SESSION_KEY_CAMPAIGNID = "campainID";
    const SESSION_KEY_AD_GROUP_ID = "adgroupId";
    const SESSION_KEY_AD_REPORT_ID = "adReportId";
    const SESSION_KEY_ACCOUNT_ID = "accountID";
    const SESSION_KEY_KEYWORD_ID = "KeywordID";
    const SESSION_KEY_ENGINE = "engine";
    const SESSION_KEY_OLD_ENGINE = 'oldEngine';
    const SESSION_KEY_OLD_ACCOUNT_ID = 'oldAccountId';
    const SESSION_KEY_CLIENT_ID = 'clientId';
    const SESSION_KEY_AGENCY_ID = 'agencyId';
    const PREFECTURE = 'prefecture';
    private $adgainerId;
    protected $displayNoDataFoundMessageOnGraph = true;
    protected $displayNoDataFoundMessageOnTable = true;

    protected $page = 1;
    /**
     * AbstractReportController constructor.
     *
     * @param ResponseFactory     $responseFactory
     * @param AbstractReportModel $model
     */
    public function __construct(
        ResponseFactory $responseFactory,
        AbstractReportModel $model
    ) {
        $this->responseFactory = $responseFactory;
        $this->model = $model;
        $this->middleware('auth');
        $this->middleware('language');
        $this->middleware(
            function (Request $request, $next) {
                if (!\Auth::check()) {
                    return redirect('/login');
                }
                $this->adgainerId = \Auth::id(); // you can access user id here

                return $next($request);
            }
        );
    }

    protected function translateFieldNames(array $fieldNames)
    {
        $translatedFieldNames = [];
        foreach ($fieldNames as $fieldName) {
            $translatedFieldNames[] = __('language.' . strtolower($fieldName));
        }

        return $translatedFieldNames;
    }

    protected function updateNumberPage($page)
    {
        $this->page = $page;
    }

    public function displayGraph(Request $request)
    {
        $this->updateModel();
        $this->updateSessionData($request);
        try {
            $data = $this->getDataForGraph();
        } catch (Exception $exception) {
            return $this->generateJSONErrorResponse($exception);
        }
        $timePeriodLayout = view('layouts.time-period')
                        ->with(static::START_DAY, session(static::SESSION_KEY_START_DAY))
                        ->with(static::END_DAY, session(static::SESSION_KEY_END_DAY))
                        ->with(static::TIME_PERIOD_TITLE, session(static::SESSION_KEY_TIME_PERIOD_TITLE))
                        ->render();
        $statusLayout = view('layouts.status-title')
                        ->with(static::STATUS_TITLE, session(static::SESSION_KEY_STATUS_TITLE))
                        ->render();
        foreach ($data as $value) {
            // if data !== null, display on graph
            // else, display "no data found" message
            if ($value['data'] !== null) {
                $this->displayNoDataFoundMessageOnGraph = false;
            }
        }
        return $this->responseFactory->json(
            [
                'data' => $data,
                'field' => session(static::SESSION_KEY_GRAPH_COLUMN_NAME),
                'timePeriodLayout' => $timePeriodLayout,
                'statusLayout' => $statusLayout,
                'displayNoDataFoundMessageOnGraph' => $this->displayNoDataFoundMessageOnGraph
            ]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToExcel()
    {
        $this->updateModel();
        if (session(static::SESSION_KEY_GROUPED_BY_FIELD) === 'prefecture') {
            $this->updateModelForPrefecture();
        }
        $data = $this->getDataForTable();
        $fieldNames = session()->get(static::SESSION_KEY_FIELD_NAME);
        $fieldNames = $this->model->unsetColumns($fieldNames, [static::MEDIA_ID]);

        /** @var $collection \Illuminate\Database\Eloquent\Collection */
        $collection = $data->getCollection();

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

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToCsv()
    {
        $this->updateModel();
        if (session(static::SESSION_KEY_GROUPED_BY_FIELD) === 'prefecture') {
            $this->updateModelForPrefecture();
        }
        $data = $this->getDataForTable();

        $fieldNames = session()->get(static::SESSION_KEY_FIELD_NAME);
        $fieldNames = $this->model->unsetColumns($fieldNames, [static::MEDIA_ID]);
        /** @var $collection \Illuminate\Database\Eloquent\Collection */
        $collection = $data->getCollection();

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
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateJSONErrorResponse(Exception $exception)
    {
        $errorObject = new StdClass;
        $errorObject->code = 500;
        $errorObject->error = $exception->getMessage();

        return $this->responseFactory->json($errorObject, 500);
    }

    public function initializeSession(array $columns)
    {
        $today = new DateTime;
        $endDay = $today->format('Y-m-d');
        $startDay = $today->modify('-90 days')->format('Y-m-d');
        $timePeriodTitle = "Last 90 days";
        $accountStatus = "showZero";
        $statusTitle = "Show 0";
        $graphColumnName = "impressions";
        $summaryReport = [
            'clicks',
            'impressions',
            'cost',
            'averageCpc',
            'averagePosition'
        ];
        session([static::SESSION_KEY_FIELD_NAME => $columns]);
        session([static::SESSION_KEY_ACCOUNT_STATUS => $accountStatus]);
        session([static::SESSION_KEY_TIME_PERIOD_TITLE => $timePeriodTitle]);
        session([static::SESSION_KEY_STATUS_TITLE => $statusTitle]);
        session([static::SESSION_KEY_START_DAY => $startDay]);
        session([static::SESSION_KEY_END_DAY => $endDay]);
        session([static::SESSION_KEY_PAGINATION => 20]);
        session([static::SESSION_KEY_GRAPH_COLUMN_NAME => $graphColumnName]);
        session([static::SESSION_KEY_COLUMN_SORT => 'impressions']);
        session([static::SESSION_KEY_SORT => 'desc']);
        session([static::SESSION_KEY_SUMMARY_REPORT => $summaryReport]);
        if (session(self::SESSION_KEY_ENGINE) === 'yss'
            || session(self::SESSION_KEY_ENGINE) === 'ydn'
            || session(self::SESSION_KEY_ENGINE) === null
        ) {
            session([static::SESSION_KEY_GROUPED_BY_FIELD => static::GROUPED_BY_FIELD]);
        } elseif (session(self::SESSION_KEY_ENGINE) === 'adw') {
            session([static::SESSION_KEY_GROUPED_BY_FIELD => static::ADW_GROUPED_BY_FIELD]);
        }
        if (session('accountID') === null) {
            session([self::SESSION_KEY_ACCOUNT_ID => null]);
        }
        if (session('campainID') === null) {
            session([self::SESSION_KEY_CAMPAIGNID => null]);
        }
        if (session('adgroupId') === null) {
            session([self::SESSION_KEY_AD_GROUP_ID => null]);
        }
        if (session('adReportId') === null) {
            session([self::SESSION_KEY_AD_REPORT_ID => null]);
        }
        if (session(self::SESSION_KEY_ENGINE) === null) {
            session([self::SESSION_KEY_ENGINE => null]);
        }
    }

    public function updateGroupByFieldWhenSessionEngineChange(array $columns)
    {
        if (session(self::SESSION_KEY_ENGINE) === 'yss'
            || session(self::SESSION_KEY_ENGINE) === 'ydn'
            || session(self::SESSION_KEY_ENGINE) === null
        ) {
            session([static::SESSION_KEY_GROUPED_BY_FIELD => static::GROUPED_BY_FIELD]);
        } elseif (session(self::SESSION_KEY_ENGINE) === 'adw') {
            session([static::SESSION_KEY_GROUPED_BY_FIELD => static::ADW_GROUPED_BY_FIELD]);
        }

        session([static::SESSION_KEY_FIELD_NAME => $columns]);
        session()->put([self::SESSION_KEY_OLD_ACCOUNT_ID => session(self::SESSION_KEY_ACCOUNT_ID)]);
        session()->put([self::SESSION_KEY_OLD_ENGINE => session(self::SESSION_KEY_ENGINE)]);
    }

    public function checkoutSessionFieldName()
    {
        if (session(static::SESSION_KEY_FIELD_NAME)) {
            if (session(static::SESSION_KEY_FIELD_NAME)[0] === 'device'
                || session(static::SESSION_KEY_FIELD_NAME)[0] === 'hourofday'
                || session(static::SESSION_KEY_FIELD_NAME)[0] === 'dayOfWeek'
                || session(static::SESSION_KEY_FIELD_NAME)[0] === 'prefecture'
            ) {
                $fieldNames = session(static::SESSION_KEY_FIELD_NAME);
                $fieldNames[0] = session(static::SESSION_KEY_GROUPED_BY_FIELD);
                session()->put([static::SESSION_KEY_FIELD_NAME => $fieldNames]);
            }
        }
    }

    public function updateSessionGraphColumnName($graphColumnName)
    {
        session()->put(static::SESSION_KEY_GRAPH_COLUMN_NAME, $graphColumnName);
    }

    public function updateSessionFieldNameAndPagination($fieldName, $pagination)
    {
        array_unshift($fieldName, session(static::SESSION_KEY_GROUPED_BY_FIELD));
        if (!in_array(session(static::SESSION_KEY_COLUMN_SORT), $fieldName)) {
            $positionOfFirstFieldName = 1;
            session()->put(static::SESSION_KEY_COLUMN_SORT, $fieldName[$positionOfFirstFieldName]);
        }
        session()->put(
            [
            static::SESSION_KEY_FIELD_NAME => $fieldName,
            static::SESSION_KEY_PAGINATION => $pagination
            ]
        );
    }

    public function updateSessionStartDayAndEndDayAndTimePeriodTitle($startDay, $endDay, $timePeriodTitle)
    {
        session()->put(
            [
            static::SESSION_KEY_START_DAY => $startDay,
            static::SESSION_KEY_END_DAY => $endDay,
            static::SESSION_KEY_TIME_PERIOD_TITLE => $timePeriodTitle
            ]
        );
    }

    public function updateSessionStatus($status)
    {
        session()->put([static::SESSION_KEY_ACCOUNT_STATUS => $status]);
    }

    public function updateSessionStatusTitle($statusTitle)
    {
        session()->put([static::SESSION_KEY_STATUS_TITLE => $statusTitle]);
    }

    public function updateSessionClientId($clientId)
    {
        session()->put(
            [
                self::SESSION_KEY_CLIENT_ID => $clientId
            ]
        );
    }
    public function updateSessionAgencyId($agencyId)
    {
        session()->put(
            [
                self::SESSION_KEY_AGENCY_ID => $agencyId
            ]
        );
    }

    public function updateSessionAccountId($accountId)
    {
        session()->put(
            [
                self::SESSION_KEY_ACCOUNT_ID => $accountId
            ]
        );
        if (!session()->has(self::SESSION_KEY_OLD_ACCOUNT_ID)) {
            session()->put([self::SESSION_KEY_OLD_ACCOUNT_ID => session(self::SESSION_KEY_ACCOUNT_ID)]);
        }
    }

    public function updateSessionAdReportId($adReportId)
    {
        session()->put(
            [
                self::SESSION_KEY_AD_REPORT_ID => $adReportId
            ]
        );
    }

    public function updateSessionCampaignId($campaignId)
    {
        session()->put(
            [
                self::SESSION_KEY_CAMPAIGNID => $campaignId
            ]
        );
    }

    public function updateSessionAdGroupId($adGroupId)
    {
        session()->put(
            [
                self::SESSION_KEY_AD_GROUP_ID=> $adGroupId
            ]
        );
    }

    public function updateSessionKeywordId($keywordId)
    {
        session()->put(
            [
                self::SESSION_KEY_KEYWORD_ID => $keywordId
            ]
        );
    }

    public function updateSessionColumnSortAndSort($columnSort)
    {
        if ($columnSort === 'agencyName') {
            session([static::SESSION_KEY_COLUMN_SORT => 'agencyName']);
        }
        if (session(static::SESSION_KEY_COLUMN_SORT) !== $columnSort
            || session(static::SESSION_KEY_SORT) !== 'desc'
        ) {
            session()->put(
                [
                static::SESSION_KEY_COLUMN_SORT => $columnSort,
                static::SESSION_KEY_SORT => 'desc'
                ]
            );
        } elseif (session(static::SESSION_KEY_SORT) !== 'asc') {
            session()->put(
                [
                static::SESSION_KEY_COLUMN_SORT => $columnSort,
                static::SESSION_KEY_SORT => 'asc'
                ]
            );
        }
    }

    public function updateSessionGroupedByFieldName($specificItem)
    {
        $array = session(static::SESSION_KEY_FIELD_NAME);
        $array[0] = $specificItem;
        session()->put([static::SESSION_KEY_FIELD_NAME => $array]);
        session()->put([static::SESSION_KEY_GROUPED_BY_FIELD => $specificItem]);
    }

    public function updateSessionEngine($engine)
    {
        // the first time we will update session key engine,
        // after that we check if old engine doesn't exit, we will update session for old engine.
        session()->put([self::SESSION_KEY_ENGINE => $engine]);
        if (!session()->has(self::SESSION_KEY_OLD_ENGINE)) {
            session()->put([self::SESSION_KEY_OLD_ENGINE => session(self::SESSION_KEY_ENGINE)]);
        }
    }

    public function updateNormalReport()
    {
        $array = session(static::SESSION_KEY_FIELD_NAME);

        if (session(static::SESSION_KEY_ENGINE) === 'yss'
            || session(static::SESSION_KEY_ENGINE) === 'ydn'
            || session(static::SESSION_KEY_ENGINE) === null
        ) {
            $array[0] = static::GROUPED_BY_FIELD;
            session()->put([static::SESSION_KEY_GROUPED_BY_FIELD => static::GROUPED_BY_FIELD]);
        } elseif (session(static::SESSION_KEY_ENGINE) === 'adw') {
            $array[0] = static::ADW_GROUPED_BY_FIELD;
            session()->put([static::SESSION_KEY_GROUPED_BY_FIELD => static::ADW_GROUPED_BY_FIELD]);
        }
        session()->put([static::SESSION_KEY_FIELD_NAME => $array]);
    }

    public function updateSessionID(Request $request)
    {
        $this->updateSessionData($request);
    }

    public function updateSessionData(Request $request)
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
        if ($request->status !== null) {
            $this->updateSessionStatus($request->status);
        }

        // get statusTitle if available
        if ($request->statusTitle !== null) {
            $this->updateSessionStatusTitle($request->statusTitle);
        }

        // get id account media if available
        if ($request->id_account === 'all') {
            session()->put(
                [
                self::SESSION_KEY_ACCOUNT_ID => null
                ]
            );
        } elseif ($request->id_account !== "all" && $request->id_account !== null) {
            $this->updateSessionAccountId($request->id_account);
        }

        //get id client if avaiable
        if ($request->id_client === 'all') {
            session()->put(
                [
                    self::SESSION_KEY_CLIENT_ID => null
                ]
            );
        } elseif ($request->id_client !== "all" && $request->id_client !== null) {
            $this->updateSessionClientId($request->id_client);
        }

        //get id agency if avaiable
        if ($request->id_agency === 'all') {
            session()->put(
                [
                    self::SESSION_KEY_AGENCY_ID => null
                ]
            );
        } elseif ($request->id_agency !== "all" && $request->id_agency !== null) {
            $this->updateSessionAgencyId($request->id_agency);
        }

        //get id campaign if avaiable
        if ($request->id_campaign === 'all') {
            session()->put(
                [
                self::SESSION_KEY_CAMPAIGNID => null
                ]
            );
        } elseif ($request->id_campaign !== "all" && $request->id_campaign !== null) {
            $this->updateSessionCampaignId($request->id_campaign);
        }

        //get id adGroup if avaiable
        if ($request->id_adgroup === 'all') {
            session()->put(
                [
                self::SESSION_KEY_AD_GROUP_ID => null
                ]
            );
        } elseif ($request->id_adgroup !== "all" && $request->id_adgroup !== null) {
            $this->updateSessionAdGroupId($request->id_adgroup);
        }

        //get id adReport if avaiable
        if ($request->id_adReport === 'all') {
            session()->put(
                [
                self::SESSION_KEY_AD_REPORT_ID => null
                ]
            );
        } elseif ($request->id_adReport !== "all" && $request->id_adReport !== null) {
            $this->updateSessionAdReportId($request->id_adReport);
        }

        //get id keyword if avaiable
        if ($request->id_keyword === 'all') {
            session()->put(
                [
                self::SESSION_KEY_KEYWORD_ID => null
                ]
            );
        } elseif ($request->id_keyword !== "all" && $request->id_keyword !== null) {
            $this->updateSessionKeywordId($request->id_keyword);
        }

        //get column sort and sort by if available
        if ($request->columnSort !== null) {
            $this->updateSessionColumnSortAndSort($request->columnSort);
        }

        //get engine if available
        if ($request->engine !== null) {
            $this->updateSessionEngine($request->engine);
        }

        if ($request->specificItem !== null) {
            $this->updateSessionGroupedByFieldName($request->specificItem);
        }

        if ($request->normalReport !== null) {
            $this->updateNormalReport();
        }
    }

    public function getDataForGraph()
    {
        $data = $this->model->getDataForGraph(
            session(self::SESSION_KEY_ENGINE),
            session(static::SESSION_KEY_GRAPH_COLUMN_NAME),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session(self::SESSION_KEY_AGENCY_ID),
            session(self::SESSION_KEY_ACCOUNT_ID),
            session(self::SESSION_KEY_CLIENT_ID),
            session(self::SESSION_KEY_CAMPAIGNID),
            session(self::SESSION_KEY_AD_GROUP_ID),
            session(self::SESSION_KEY_AD_REPORT_ID),
            session(self::SESSION_KEY_KEYWORD_ID)
        );

        if ($data->isEmpty()) {
                $data[] = ['day' => session(static::SESSION_KEY_END_DAY), 'data' => null];
                $data[] = ['day' => session(static::SESSION_KEY_START_DAY), 'data' => null];
        }

        return $data;
    }

    public function getDataForTable()
    {
        if (!in_array(session(static::SESSION_KEY_COLUMN_SORT), session(static::SESSION_KEY_FIELD_NAME))) {
            if (session(static::SESSION_KEY_COLUMN_SORT) !== 'agencyName') {
                session([static::SESSION_KEY_COLUMN_SORT => session(static::SESSION_KEY_FIELD_NAME)[0]]);
            }
        }

        return $this->model->getDataForTable(
            session(self::SESSION_KEY_ENGINE),
            session(static::SESSION_KEY_FIELD_NAME),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session(static::SESSION_KEY_PAGINATION),
            session(static::SESSION_KEY_COLUMN_SORT),
            session(static::SESSION_KEY_SORT),
            session(static::SESSION_KEY_GROUPED_BY_FIELD),
            session(self::SESSION_KEY_AGENCY_ID),
            session(self::SESSION_KEY_ACCOUNT_ID),
            session(self::SESSION_KEY_CLIENT_ID),
            session(self::SESSION_KEY_CAMPAIGNID),
            session(self::SESSION_KEY_AD_GROUP_ID),
            session(self::SESSION_KEY_AD_REPORT_ID),
            session(self::SESSION_KEY_KEYWORD_ID)
        );
    }

    public function getCalculatedSummaryReport()
    {
        return $this->model->calculateSummaryData(
            session(self::SESSION_KEY_ENGINE),
            session(static::SESSION_KEY_SUMMARY_REPORT),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session(self::SESSION_KEY_AGENCY_ID),
            session(static::SESSION_KEY_ACCOUNT_ID),
            session(self::SESSION_KEY_CLIENT_ID),
            session(static::SESSION_KEY_CAMPAIGNID),
            session(static::SESSION_KEY_AD_GROUP_ID),
            session(static::SESSION_KEY_AD_REPORT_ID),
            session(static::SESSION_KEY_KEYWORD_ID)
        );
    }

    public function getCalculatedData()
    {
        return $this->model->calculateData(
            session(self::SESSION_KEY_ENGINE),
            session(static::SESSION_KEY_FIELD_NAME),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session(static::SESSION_KEY_GROUPED_BY_FIELD),
            session(self::SESSION_KEY_AGENCY_ID),
            session(self::SESSION_KEY_ACCOUNT_ID),
            session(self::SESSION_KEY_CLIENT_ID),
            session(self::SESSION_KEY_CAMPAIGNID),
            session(self::SESSION_KEY_AD_GROUP_ID),
            session(self::SESSION_KEY_AD_REPORT_ID),
            session(self::SESSION_KEY_KEYWORD_ID)
        );
    }

    public function getModelForPrefecture()
    {
        $fieldNames = session(static::SESSION_KEY_FIELD_NAME);
        if (session(static::SESSION_KEY_GROUPED_BY_FIELD) === self::PREFECTURE) {
            $this->updateModelForPrefecture();
            $fieldNames = $this->model->unsetColumns($fieldNames, ['impressionShare']);
            session()->put([static::SESSION_KEY_FIELD_NAME => $fieldNames]);
            $this->updateModelForPrefecture();
        } else {
            session()->put([static::SESSION_KEY_FIELD_NAME => $fieldNames]);
        }
    }

    public function updateModelForPrefecture()
    {
        if (session(self::SESSION_KEY_ENGINE) === 'yss') {
            $this->model = new RepoYssPrefectureReportCost;
        } elseif (session(self::SESSION_KEY_ENGINE) === 'ydn') {
            $this->model = new RepoYdnPrefecture;
        } elseif (session(self::SESSION_KEY_ENGINE) === 'adw') {
            $this->model = new RepoAdwGeoReportCost;
        }
    }

    public function updateModelForTimezone()
    {
        if (session(self::SESSION_KEY_ENGINE) === 'yss') {
            // TODO: change model to yss hourOfDay
        } elseif (session(self::SESSION_KEY_ENGINE) === 'ydn') {
            $this->model = new RepoYdnTimezone;
        } elseif (session(self::SESSION_KEY_ENGINE) === 'adw') {
            // TODO: change model to adw hourOfDay
        }
    }

    public function exportSearchQueryToCsv()
    {
        $fieldNames = session()->get(static::SESSION_KEY_FIELD_NAME);
        if (session(static::SESSION_KEY_ENGINE) === 'yss') {
            $this->model = new RepoYssSearchqueryReportCost;
            $fieldNames[0] = 'searchQuery';
            session()->put([static::SESSION_KEY_GROUPED_BY_FIELD => 'searchQuery']);
            session()->put([static::SESSION_KEY_FIELD_NAME => $fieldNames]);
        } elseif (session(static::SESSION_KEY_ENGINE) === 'adw') {
            $this->model = new RepoAdwSearchQueryPerformanceReport;
            $fieldNames[0] = 'searchTerm';
            session()->put([static::SESSION_KEY_GROUPED_BY_FIELD => 'searchTerm']);
            session()->put([static::SESSION_KEY_FIELD_NAME => $fieldNames]);
        }
        $data = $this->getDataForTable();
        $collection = $data->getCollection();
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

    public function exportSearchQueryToExcel()
    {
        $fieldNames = session()->get(static::SESSION_KEY_FIELD_NAME);
        if (session(static::SESSION_KEY_ENGINE) === 'yss') {
            $this->model = new RepoYssSearchqueryReportCost;
            $fieldNames[0] = 'searchQuery';
            session()->put([static::SESSION_KEY_GROUPED_BY_FIELD => 'searchQuery']);
            session()->put([static::SESSION_KEY_FIELD_NAME => $fieldNames]);
        } elseif (session(static::SESSION_KEY_ENGINE) === 'adw') {
            $this->model = new RepoAdwSearchQueryPerformanceReport;
            $fieldNames[0] = 'searchTerm';
            session()->put([static::SESSION_KEY_GROUPED_BY_FIELD => 'searchTerm']);
            session()->put([static::SESSION_KEY_FIELD_NAME => $fieldNames]);
        }
        $data = $this->getDataForTable();
        $fieldNames = session()->get(static::SESSION_KEY_FIELD_NAME);
        $fieldNames = $this->model->unsetColumns($fieldNames, [static::MEDIA_ID]);

        /** @var $collection \Illuminate\Database\Eloquent\Collection */
        $collection = $data->getCollection();

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

    public function checkoutConditionForUpdateColumn($engine)
    {
        if (session(self::SESSION_KEY_OLD_ENGINE) === $engine) {
            if (session(self::SESSION_KEY_OLD_ACCOUNT_ID) === session(self::SESSION_KEY_ACCOUNT_ID)) {
                return false; // same campaign => no update
            }
            return true; // same engine, different account id => update back to normal report
        } else {
            return true; // different engine => update back to normal report
        }
    }

    public function updateColumnAccountNameToClientNameOrAgencyName(array $columns, $prefixRoute)
    {
        foreach ($columns as $key => $value) {
            if ($value === 'accountName' && $prefixRoute === '/client-report') {
                $columns[$key] = 'clientName';
                break;
            } elseif ($value === 'accountName' && $prefixRoute === '/agency-report') {
                $columns[$key] = 'agencyName';
                break;
            }
        }
        return $columns;
    }
}
