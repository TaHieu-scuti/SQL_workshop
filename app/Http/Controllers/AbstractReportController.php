<?php

namespace App\Http\Controllers;

use App\AbstractReportModel;

use App\Export\MaatwebsiteCSVExporter;
use App\Export\MaatwebsiteExcelExporter;

abstract class AbstractReportController extends Controller
{
    /** @var \App\AbstractReportModel */
    protected $model;

    /**
     * @param AbstractReportModel $model
     */
    public function __construct(AbstractReportModel $model)
    {
        $this->model = $model;
    }

    public function exportToExcel()
    {
        $exporter = new MaatwebsiteExcelExporter($this->model);
        $exporter->export();
    }

    public function exportToCsv()
    {
        $exporter = new MaatwebsiteCSVExporter($this->model);
        $exporter->export();
    }
}
