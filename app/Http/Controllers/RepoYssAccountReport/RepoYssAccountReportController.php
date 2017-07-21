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
    public function index()
    {
        $yssAccountReports =  RepoYssAccountReport::all();
        return view('yssAccountReport.index')->with('yssAccountReports', $yssAccountReports);
    }
}
