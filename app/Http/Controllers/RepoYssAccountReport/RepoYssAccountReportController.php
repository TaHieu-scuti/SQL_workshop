<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RepoYssAccountReport;

class RepoYssAccountReportController extends Controller
{
    /**
     * Display a listing of account report
     *
     * @return \Illuminate\Http\Response
     */
    private $RepoYssAccountReport;

    public function __construct(RepoYssAccountReport $RepoYssAccountReport)
    {
        $this->RepoYssAccountReport = $RepoYssAccountReport;
    }

    // public function index($yssAccountPerPage,
    //                       $account_id = null,
    //                       $clicks = null,
    //                       $impressions = null,
                          
    // )
    // {
    //     $yssAccountReports =  $this->RepoYssAccountReport
    //                          -> where('account_id', 'thisADgainerId')
    //                          ->paginate($yssAccountPerPage);
    //     return view('yssAccountReport.index')->with('yssAccountReports', $yssAccountReports);
    // }
    public function index()
    {
        // $accountReportSession = ['account_id', 'clicks', 'impressions', 'ctr', 'averageCpc',
        //          'averagePosition', 'invalidClicks', 'invalidClickRate',
        //          'impressionShare', 'exactMatchImpressionShare', 'budgetLostImpressionShare',
        //          'qualityLostImpressionShare', 'trackingURL', 'conversions',
        //          'convRate', 'convValue', 'costPerConv', 'valuePerConv', 'allConv',
        //          'allConvRate', 'allConvValue', 'costPerAllConv', 'valuePerAllConv',
        //          'network', 'device', 'day', 'dayOfWeek', 'quarter', 'month', 'week'
        //          ];
        // if $accountReportSession[] = null
        $yssAccountReports =  $this->RepoYssAccountReport
                            ->paginate(15);
        return view('yssAccountReport.index')->with('yssAccountReports', $yssAccountReports);
    }

    public function test(Request $request)
    {
        // $yssAccountReports = $this->RepoYssAccountReport->paginate($request->pagination);
        // dd($request->fieldName);
        $yssAccountReports = $this->RepoYssAccountReport->select($request->fieldName)->paginate($request->pagination);
        return response()->json($yssAccountReports);
    }
}
