<?php
namespace App\Export;

interface CSVExporterInterface
{
    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return string
     */
    public function export($sessionKeyPrefix, $data);
}
