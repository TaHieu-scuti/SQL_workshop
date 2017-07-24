<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RepoYssAccountReport;

const YSS_ACCOUNT_PER_PAGE = '5';
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

    public function index()
    {
        $yssAccountReports =  $this->RepoYssAccountReport->paginate(YSS_ACCOUNT_PER_PAGE);
        return view('yssAccountReport.index')->with('yssAccountReports', $yssAccountReports);
    }
}
