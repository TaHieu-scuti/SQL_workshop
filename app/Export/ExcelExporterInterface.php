<?php
namespace App\Export;

interface ExcelExporterInterface
{
    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return string
     */
    public function export();
}
