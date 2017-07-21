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

    public function index()
    {
        $yssAccountReports =  $this->RepoYssAccountReport->getAllData();
        return view('yssAccountReport.index')->with('yssAccountReports', $yssAccountReports);
    }

    public function test()
    {
        $test = new RepoYssAccountReport();
    }
}
