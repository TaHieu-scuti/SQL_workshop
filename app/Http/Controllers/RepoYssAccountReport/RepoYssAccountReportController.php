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
        return view('yssAccountReport.index');
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

    public function getAllData(Request $request)
    {
        $fieldName = $request->fieldName;
        array_unshift($fieldName, 'account_id');
        if(!session('accountReport')) {
            session(['accountReport' => [
                'fieldName' => $fieldName,
                'pagination' => 20,
            ]]);
        }

        $yssAccountReports = $this->RepoYssAccountReport->getDataByFilter(session('accountReport')['fieldName'], session('accountReport')['pagination'])->toArray()['data'];
        return response()->json(view('yssAccountReport.table_data')->with('yssAccountReports', $yssAccountReports)->with('fieldName', session('accountReport')['fieldName'])->render());
    }

    public function getDataByFilter(Request $request)
    {
        if ($request->fieldName === null) {
            session()->put('accountReport.pagination',$request->pagination);
        } else {
            $fieldName = $request->fieldName;
            array_unshift($fieldName, 'account_id');
            session()->put('accountReport', [
                'fieldName' => $fieldName,
                'pagination' => $request->pagination,
            ]);
        }

        $yssAccountReports = $this->RepoYssAccountReport->getDataByFilter(session('accountReport')['fieldName'], session('accountReport')['pagination']);
        $filteredData = $yssAccountReports->toArray()['data'];
        return response()->json(view('yssAccountReport.table_data')->with('yssAccountReports', $filteredData)->with('fieldName', session('accountReport')['fieldName'])->render());
    }
}
