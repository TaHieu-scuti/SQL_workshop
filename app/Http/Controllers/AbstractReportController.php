<?php

namespace App\Http\Controllers;

use App\AbstractReportModel;
use App\Export\Native\NativePHPCsvExporter;
use App\Export\Spout\SpoutExcelExporter;
use Illuminate\Http\Request;

use Illuminate\Contracts\Routing\ResponseFactory;

use DateTime;
use Exception;
use StdClass;
use Auth;

abstract class AbstractReportController extends Controller
{
    /** @var \Illuminate\Contracts\Routing\ResponseFactory */
    protected $responseFactory;

    /** @var \App\AbstractReportModel */
    protected $model;
    public $sessionKeyCampaignId = "campainID";
    public $sessionKeyAdgroupId = "adgroupId";
    public $sessionKeyAdReportId = "adReportId";
    public $sessionKeyAccountId = "accountID";
    public $sessionKeywordId = "KeywordID";
    private $adgainerId;

    /**
     * AbstractReportController constructor.
     * @param ResponseFactory $responseFactory
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
        $this->middleware(function (Request $request, $next) {
            if (!\Auth::check()) {
                return redirect('/login');
            }
            $this->adgainerId = \Auth::id(); // you can access user id here

           return $next($request);
        });

    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToExcel()
    {
        $exporter = new SpoutExcelExporter($this->model);
        $excelData = $exporter->export(static::SESSION_KEY_PREFIX);

        return $this->responseFactory->make($excelData, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $exporter->getFileName() . '"',
            'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => (new DateTime)->format('D, d M Y H:i:s'),
            'Cache-Control' => 'cache, must-revalidate, private',
            'Pragma' => 'public'
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToCsv()
    {
        $exporter = new NativePHPCsvExporter($this->model);
        $csvData = $exporter->export(static::SESSION_KEY_PREFIX);

        return $this->responseFactory->make($csvData, 200, [
            'Content-Type' => 'application/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $exporter->getFileName() . '"',
            'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => (new DateTime)->format('D, d M Y H:i:s'),
            'Cache-Control' => 'cache, must-revalidate, private',
            'Pragma' => 'public'
        ]);
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
        $accountStatus = "hideZero";
        $statusTitle = "Hide 0";
        $graphColumnName = "clicks";
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
        session([$this->sessionKeyAccountId => null]);
        session([$this->sessionKeyCampaignId => null]);
        session([$this->sessionKeyAdgroupId => null]);
        session([$this->sessionKeyAdReportId => null]);

    }

    public function updateSessionGraphColumnName($graphColumnName)
    {
        session()->put(static::SESSION_KEY_GRAPH_COLUMN_NAME, $graphColumnName);
    }

    public function updateSessionFieldNameAndPagination($fieldName, $pagination)
    {
        array_unshift($fieldName, static::SESSION_KEY_GROUPED_BY_FIELD);
        if (!in_array(session(static::SESSION_KEY_COLUMN_SORT), $fieldName)) {
            $positionOfFirstFieldName = 1;
            session()->put(static::SESSION_KEY_COLUMN_SORT, $fieldName[$positionOfFirstFieldName]);
        }
        session()->put([
            static::SESSION_KEY_FIELD_NAME => $fieldName,
            static::SESSION_KEY_PAGINATION => $pagination
        ]);
    }

    public function updateSessionStartDayAndEndDayAndTimePeriodTitle($startDay, $endDay, $timePeriodTitle)
    {
        session()->put([
            static::SESSION_KEY_START_DAY => $startDay,
            static::SESSION_KEY_END_DAY => $endDay,
            static::SESSION_KEY_TIME_PERIOD_TITLE => $timePeriodTitle
        ]);
    }

    public function updateSessionStatus($status)
    {
        session()->put([static::SESSION_KEY_ACCOUNT_STATUS => $status]);
    }

    public function updateSessionStatusTitle($statusTitle)
    {
        session()->put([static::SESSION_KEY_STATUS_TITLE => $statusTitle]);
    }

    public function updateSessionAccountId($accountId)
    {
        session()->put([
                $this->sessionKeyAccountId => $accountId
            ]);
    }

    public function updateSessionAdReportId($adReportId)
    {
        session()->put([
                $this->sessionKeyAdReportId => $adReportId
            ]);
    }

    public function updateSessionCampaignId($campaignId)
    {
        session()->put([
                $this->sessionKeyCampaignId => $campaignId
            ]);
    }

    public function updateSessionAdGroupId($adGroupId)
    {
        session()->put([
                $this->sessionKeyAdgroupId => $adGroupId
            ]);
    }

    public function updateSessionKeywordId($keywordId)
    {
        session()->put([
                $this->sessionKeywordId => $keywordId
            ]);
    }

    public function updateSessionColumnSortAndSort($columnSort)
    {
        if (session(static::SESSION_KEY_COLUMN_SORT) !== $columnSort
            || session(static::SESSION_KEY_SORT) !== 'desc') {
            session()->put([
                static::SESSION_KEY_COLUMN_SORT => $columnSort,
                static::SESSION_KEY_SORT => 'desc'
            ]);
        } elseif (session(static::SESSION_KEY_SORT) !== 'asc') {
            session()->put([
                static::SESSION_KEY_COLUMN_SORT => $columnSort,
                static::SESSION_KEY_SORT => 'asc'
            ]);
        }
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

        // get id account media if available
        if ($request->id_account === 'all') {
            session()->put([
                $this->sessionKeyAccountId => null
            ]);
        } elseif ($request->id_account !== "all" && $request->id_account !== null) {
            $this->updateSessionAccountId($request->id_account);
        }

        //get id campaign if avaiable
        if ($request->id_campaign === 'all') {
            session()->put([
                $this->sessionKeyCampaignId => null
            ]);
        } elseif ($request->id_campaign !== "all" && $request->id_campaign !== null) {
            $this->updateSessionCampaignId($request->id_campaign);
        }

        //get id adGroup if avaiable
        if ($request->id_adgroup === 'all') {
            session()->put([
                $this->sessionKeyAdgroupId => null
            ]);
        } elseif ($request->id_adgroup !== "all" && $request->id_adgroup !== null) {
            $this->updateSessionAdGroupId($request->id_adgroup);
        }

        //get id adReport if avaiable
        if ($request->id_adReport === 'all') {
            session()->put([
                $this->sessionKeyAdReportId => null
            ]);
        } elseif ($request->id_adReport !== "all" && $request->id_adReport !== null) {
            $this->updateSessionAdReportId($request->id_adReport);
        }

        //get id keyword if avaiable
        if ($request->id_keyword === 'all') {
            session()->put([
                $this->sessionKeywordId => null
            ]);
        } elseif ($request->id_keyword !== "all" && $request->id_keyword !== null) {
            $this->updateSessionKeywordId($request->id_keyword);
        }

        //get column sort and sort by if available
        if ($request->columnSort !== null) {
            $this->updateSessionColumnSortAndSort($request->columnSort);
        }
    }

    public function getDataForGraph()
    {
        $data = $this->model->getDataForGraph(
            session(static::SESSION_KEY_GRAPH_COLUMN_NAME),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session($this->sessionKeyAccountId),
            $this->adgainerId,
            session($this->sessionKeyCampaignId),
            session($this->sessionKeyAdgroupId),
            session($this->sessionKeyAdReportId),
            session($this->sessionKeywordId)
        );

        if ($data->isEmpty()) {
            if (session(static::SESSION_KEY_END_DAY) === session(static::SESSION_KEY_START_DAY)) {
                $data[] = ['day' => session(static::SESSION_KEY_START_DAY), 'data' => null];
            } else {
                $data[] = ['day' => session(static::SESSION_KEY_END_DAY), 'data' => null];
                $data[] = ['day' => session(static::SESSION_KEY_START_DAY), 'data' => null];
            }
        }

        return $data;
    }

    public function getDataForTable()
    {
        return $this->model->getDataForTable(
            session(static::SESSION_KEY_FIELD_NAME),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session(static::SESSION_KEY_PAGINATION),
            session(static::SESSION_KEY_COLUMN_SORT),
            session(static::SESSION_KEY_SORT),
            session($this->sessionKeyAccountId),
            $this->adgainerId,
            session($this->sessionKeyCampaignId),
            session($this->sessionKeyAdgroupId),
            session($this->sessionKeyAdReportId),
            session($this->sessionKeywordId)
        );
    }

    public function getCalculatedSummaryReport()
    {
        return $this->model->calculateSummaryData(
            session(static::SESSION_KEY_SUMMARY_REPORT),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session($this->sessionKeyAccountId),
            $this->adgainerId,
            session($this->sessionKeyCampaignId),
            session($this->sessionKeyAdgroupId),
            session($this->sessionKeyAdReportId),
            session($this->sessionKeywordId)
        );
    }

    public function getCalculatedData()
    {
        return $this->model->calculateData(
            session(static::SESSION_KEY_FIELD_NAME),
            session(static::SESSION_KEY_ACCOUNT_STATUS),
            session(static::SESSION_KEY_START_DAY),
            session(static::SESSION_KEY_END_DAY),
            session($this->sessionKeyAccountId),
            $this->adgainerId,
            session($this->sessionKeyCampaignId),
            session($this->sessionKeyAdgroupId),
            session($this->sessionKeyAdReportId),
            session($this->sessionKeywordId)
        );
    }
}
