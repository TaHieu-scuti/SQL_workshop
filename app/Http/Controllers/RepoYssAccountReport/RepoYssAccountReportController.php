<?php

namespace App\Http\Controllers\RepoYssAccountReport;

use App\Http\Controllers\AbstractReportController;
use App\RepoYssAccountReport;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Classes\FormatIdentifier;

class RepoYssAccountReportController extends AbstractReportController
{
    /**
     * @param RepoYssAccountReport $model
     */
    public function __construct(
        ResponseFactory $responseFactory,
        FormatIdentifier $formatIdentifier,
        RepoYssAccountReport $model
    ) {
        parent::__construct($responseFactory, $formatIdentifier, $model);
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
}
