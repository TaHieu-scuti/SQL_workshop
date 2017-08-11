<?php
namespace App\Export;

use App\AbstractReportModel;

use Maatwebsite\Excel\Facades\Excel;

class MaatwebsiteExcelExporter implements ExcelExporterInterface
{
    /** @var \App\AbstractReportModel */
    private $model;
    /**
    * @param AbstractReportModel $model
    */
    public function __construct(AbstractReportModel $model)
    {
        $this->model = $model;
    }

    public function export()
    {
        // get report model fields' data
        $reports = $this->model->get();
        // get table name
        $tableName = $this->model->getTable();
        // remove pagination on view
        $export = true;
        // get fields' names
        $fieldNames = $this->model->getColumnNames();
        $fileName = date("Y_m_d") . " " . date("h_i") ." ". "$tableName";
        Excel::create($fileName, function ($excel) use ($reports, $fieldNames, $export, $tableName) {
            $excel->sheet($tableName, function ($sheet) use ($reports, $fieldNames, $export) {
                $sheet->loadView('layouts.table_data')
                      ->with('reports', $reports)
                      ->with('fieldNames', $fieldNames)
                      ->with('export', $export);
            });
        })->download('xlsx');
    }
}
