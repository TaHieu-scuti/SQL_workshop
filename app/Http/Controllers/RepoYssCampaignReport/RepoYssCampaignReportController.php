<?php

namespace App\Http\Controllers\RepoYssCampaignReport;

use App\Http\Controllers\AbstractReportController;
use App\Model\RepoYssCampaignReportCost;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class RepoYssCampaignReportController extends AbstractReportController
{
    const SESSION_KEY_PREFIX = 'campaignReport.';
    const GRAPH_COLUMN_NAME = 'graphColumnName';
    const START_DAY = 'startDay';
    const END_DAY = 'endDay';

    protected $model;

    public function __construct(
        ResponseFactory $responseFactory,
        RepoYssCampaignReportCost $model
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

    private function getDataForGraph()
    {
        $data = $this->model->newGetDataForGraph($graphColumnName, $startDay, $endDay);
        return $data;
    }

    public function index()
    {
        dd(request()->route()->getPrefix());
        return view('yssCampaignReport.index');
    }

    public function displayGraph(Request $request)
    {
        $page_prefix = $request->route()->getPrefix();
        dd($page_prefix);
        $data = $this->getDataForGraph();
    }
}
