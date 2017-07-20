<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use Illuminate\Http\Request;

class RepoYssAccountReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('yssAccountReport.index');
    }
}
