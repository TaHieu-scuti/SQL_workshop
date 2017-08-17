<?php
namespace App\Export;

use App\AbstractReportModel;

use Maatwebsite\Excel\Facades\Excel;

class MaatwebsiteExcelExporter implements ExcelExporterInterface
{
    /** @var \App\AbstractReportModel */
    private $model;

    /** @var string */
    private $fileName;

    /**
    * @param AbstractReportModel $model
    */
    public function __construct(AbstractReportModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function export()
    {
        // get report model fields' data
        $reports = $this->model->get();

        // get table name
        $tableName = $this->model->getTable();

        // get fields' names
        $fieldNames = $this->model->getColumnNames();

        $this->fileName = date("Y_m_d h_i ") . "{$tableName}";

        $excelData = Excel::create($this->fileName, function ($excel) use ($reports, $fieldNames, $tableName) {
            $excel->sheet($tableName, function ($sheet) use ($reports, $fieldNames) {
                $sheet->loadView('layouts.table_data')
                      ->with('reports', $reports)
                      ->with('fieldNames', $fieldNames)
                      ->with('export', true); // remove pagination on view

            });
        })->string('xlsx');

        $this->fileName .= '.xlsx';

        return $excelData;
    }
}
