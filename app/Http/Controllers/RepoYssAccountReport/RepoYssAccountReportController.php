<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\AbstractReportController;
use App\RepoYssAccountReport;

class RepoYssAccountReportController extends AbstractReportController
{
    /**
     * @param RepoYssAccountReport $model
     */
    public function __construct(RepoYssAccountReport $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        $columns = $this->model->getColumnNames();
        if (!session('accountReport')) {
            session([
                'accountReport' => [
                    'fieldName' => $columns,
                    'pagination' => 20,
                ]]);
        }
        $reports = $this->model
                ->getDataByFilter(
                    session('accountReport')['fieldName'],
                    session('accountReport')['pagination']
                );
        return view('yssAccountReport.index')
                ->with('fieldNames', session('accountReport')['fieldName'])
                ->with('reports', $reports)
                ->with('columns', $columns);
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

        $reports = $this->model
                            ->getDataByFilter(
                                session('accountReport')['fieldName'],
                                session('accountReport')['pagination']
                            );
        return view('layouts.table_data')
                ->with('reports', $reports)
                ->with('fieldNames', session('accountReport')['fieldName']);
    }

    public function displayDataOnGraph()
    {
        if (!session('accountReport.graphColumnName')) {
            session()->put('accountReport.graphColumnName', 'clicks');
        }
        
        $data = $this->model
                ->getDataOnGraph(
                    session('accountReport.graphColumnName'),
                    session('accountReport.accountStatus'),
                    session('accountReport.startDay'),
                    session('accountReport.endDay'),
                    session('accountReport.pagination')
                );
        if ($data->isEmpty()) {
            if (session('accountReport.endDay') === session('accountReport.startDay')) {
                $data[] = ['day' => session('accountReport.startDay'), 'data' => 0];
            } else {
                $data[] = ['day' => session('accountReport.endDay'), 'data' => 0];
                $data[] = ['day' => session('accountReport.startDay'), 'data' => 0];
            }
        }

        return response()->json($data);
    }

    public function filteredGraphByColumn(Request $request)
    {
        session()->put('accountReport.graphColumnName', $request->columnName);
        $data = $this->model
                ->getDataOnGraph(
                    session('accountReport.graphColumnName'),
                    session('accountReport.accountStatus'),
                    session('accountReport.startDay'),
                    session('accountReport.endDay'),
                    session('accountReport.pagination')
                );
        return response()->json($data);
    }

    public function filteredGraphByDate(Request $request)
    {
        session()->put([
                    'accountReport.startDay' => $request->startDay,
                    'accountReport.endDay' => $request->endDay,
                    ]);
        $data = $this->model
                        ->getDataOnGraph(
                            session('accountReport.graphColumnName'),
                            session('accountReport.accountStatus'),
                            session('accountReport.startDay'),
                            session('accountReport.endDay'),
                            session('accountReport.pagination')
                        );
        if ($data->isEmpty()) {
            if ($request->startDay === $request->endDay) {
                $data[] = ['day' => $request->startDay, 'data' => 0];
            } else {
                $data[] = ['day' => $request->endDay, 'data' => 0];
                $data[] = ['day' => $request->startDay, 'data' => 0];
            }
        }

        return response()->json($data);
    }
}
