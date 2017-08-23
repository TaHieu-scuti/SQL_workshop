<?php

namespace App\Http\Controllers;

use App\AbstractReportModel;

use App\Export\MaatwebsiteCSVExporter;
use App\Export\MaatwebsiteExcelExporter;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;

use Maatwebsite\Excel\Classes\FormatIdentifier;

use DateTime;

abstract class AbstractReportController extends Controller
{
    /** @var \Illuminate\Contracts\Routing\ResponseFactory */
    protected $responseFactory;

    /** @var \Maatwebsite\Excel\Classes\FormatIdentifier */
    protected $formatIdentifier;

    /** @var \App\AbstractReportModel */
    protected $model;

    /**
     * AbstractReportController constructor.
     * @param ResponseFactory $responseFactory
     * @param FormatIdentifier $formatIdentifier
     * @param AbstractReportModel $model
     */
    public function __construct(
        ResponseFactory $responseFactory,
        FormatIdentifier $formatIdentifier,
        AbstractReportModel $model
    ) {
        $this->responseFactory = $responseFactory;
        $this->formatIdentifier = $formatIdentifier;
        $this->model = $model;
    }

    public function exportToExcel()
    {
        $exporter = new MaatwebsiteExcelExporter($this->model);
        $exporter->export();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportToCsv()
    {
        $exporter = new MaatwebsiteCSVExporter($this->model);
        $csvData = $exporter->export();

        $format = $this->formatIdentifier->getFormatByExtension('csv');
        $contentType = $this->formatIdentifier->getContentTypeByFormat($format);

        return $this->responseFactory->make($csvData, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $exporter->getFileName() . '"',
            'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => (new DateTime)->format('D, d M Y H:i:s'),
            'Cache-Control' => 'cache, must-revalidate, private',
            'Pragma' => 'public'
        ]);
    }
}