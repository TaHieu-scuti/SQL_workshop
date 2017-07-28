<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use Maatwebsite\Excel\Facades\Excel;
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

    public function export_Excel()
    {
        # code...
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $filename =date("h:i") ." ". date("Y-m-d")." Account_Report";
        $yssAccountReports =  $this->RepoYssAccountReport->get();
        Excel::create($filename, function($excel) use($yssAccountReports)  {

            $excel->sheet('Account Report', function($sheet) use($yssAccountReports) {
                $sheet->loadView('yssAccountReport.table_report')->with('yssAccountReports', $yssAccountReports);

            });

        })->download('xlsx');
    }

    public function export_CSV()
    {
        # code...
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $filename = date("h:i"). " " . date("Y-m-d")." Account_Report";
        $yssAccountReports =  $this->RepoYssAccountReport->get();
        Excel::create($filename, function($excel) use($yssAccountReports)  {

            $excel->sheet('Account Report', function($sheet) use($yssAccountReports) {
                $sheet->loadView('yssAccountReport.table_report')->with('yssAccountReports', $yssAccountReports);

            });

        })->download('csv');
    }

    public function getDataByFilter(Request $request)
    {
        if(!session('account_report')) {
            session(['account_report' => []]);
        }
        session(['account_report' => $request->all()]);
        $filterOption = [];
        if (!isset($filterOption['fieldName'])) {
            $filterOption['fieldName'] = session('account_report')['fieldName'];
        }
        if (!isset($filterOption['pagnation'])) {
            $filterOption['pagination'] = session('account_report')['pagination'];
        }
        array_unshift($filterOption['fieldName'], 'account_id');
        $yssAccountReports = $this->RepoYssAccountReport->getDataByFilter($filterOption['fieldName'], $filterOption['pagination']);
        $filteredData = $yssAccountReports->toArray()['data'];
        return response()->json(view('yssAccountReport.table_data')->with('yssAccountReports', $filteredData)->with('fieldName', $filterOption['fieldName'])->render());
    }
}
