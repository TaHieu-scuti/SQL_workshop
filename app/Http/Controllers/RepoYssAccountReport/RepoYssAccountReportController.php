<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RepoYssAccountReport;

class RepoYssAccountReportController extends Controller
{
    private $repoYssAccountReport;
    /**
     * Display a listing of account report
     *
     * @param $repoYssAccountReport
     */
    public function __construct(RepoYssAccountReport $repoYssAccountReport)
    {
        $this->repoYssAccountReport = $repoYssAccountReport;
    }

    public function index()
    {
        if (!session('accountReport')) {
            $columns = $this->repoYssAccountReport->getColumnNames();
            session([
                'accountReport' => [
                    'fieldName' => $columns,
                    'pagination' => 20,
                ]]);
        }
        $reports = $this->repoYssAccountReport
                ->getDataByFilter(
                    session('accountReport')['fieldName'],
                    session('accountReport')['pagination']
                );
        return view('yssAccountReport.index')
                ->with('fieldNames', session('accountReport')['fieldName'])
                ->with('reports', $reports);
    }

    public function getDataByFilter(Request $request)
    {
        if ($request->fieldName === null) {
            session()->put('accountReport.pagination', $request->pagination);
        } else {
            $fieldName = $request->fieldName;
            array_unshift($fieldName, 'account_id');
            session()->put('accountReport', [
                'fieldName' => $fieldName,
                'pagination' => $request->pagination,
            ]);
        }

        $yssAccountReports = $this->repoYssAccountReport
                            ->getDataByFilter(
                                session('accountReport')['fieldName'],
                                session('accountReport')['pagination']
                            );
        return view('yssAccountReport.table_data')
                ->with('yssAccountReports', $yssAccountReports)
                ->with('fieldNames', session('accountReport')['fieldName']);
    }

    public function exportExcel()
    {
        $filename =date("h:i") ." ". date("Y-m-d")." Account_Report";
        $yssAccountReports =  $this->repoYssAccountReport->get();
        Excel::create($filename, function ($excel) use ($yssAccountReports) {

            $excel->sheet('Account Report', function ($sheet) use ($yssAccountReports) {
                $sheet->loadView('yssAccountReport.table_report')
                      ->with('yssAccountReports', $yssAccountReports);
            });
        })->download('xlsx');
    }

    public function exportCsv()
    {
        $filename = date("h:i"). " " . date("Y-m-d")." Account_Report";
        $yssAccountReports =  $this->repoYssAccountReport->get();
        Excel::create($filename, function ($excel) use ($yssAccountReports) {

            $excel->sheet('Account Report', function ($sheet) use ($yssAccountReports) {
                $sheet->loadView('yssAccountReport.table_report')
                      ->with('yssAccountReports', $yssAccountReports);
            });
        })->download('csv');
    }
}
