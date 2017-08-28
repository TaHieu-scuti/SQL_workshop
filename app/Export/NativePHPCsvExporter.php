<?php

namespace App\Export;

use App\AbstractReportModel;

use ErrorException;

class NativePHPCsvExporter implements CSVExporterInterface
{
    /** @var \App\AbstractReportModel */
    private $model;

    /** @var string */
    private $fileName;

    /** @var resource */
    private $fileHandle;

    /** @var int */
    private $fileSize = 0;

    /**
     * NativePHPCsvExporter constructor.
     * @param AbstractReportModel $model
     */
    public function __construct(AbstractReportModel $model)
    {
        $this->model = $model;
    }

    private function generateFilename()
    {
        // get table name
        $tableName = $this->model->getTable();

        $this->fileName = date("Y_m_d h_i ") . "{$tableName}" . '.csv';
    }

    private function writeLine(array $data)
    {
        $bytesWritten = fputcsv($this->fileHandle, $data);
        if ($bytesWritten === false) {
            throw new ErrorException('Failed to write line to csv!');
        }

        $this->fileSize += $bytesWritten;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    public function export()
    {
        $this->generateFilename();

        $this->fileHandle = tmpfile();

        // get fields' names
        $fieldNames = $this->model->getColumnNames();

        $this->writeLine($fieldNames);

        $this->model->each(
            function (AbstractReportModel $value) {
                $this->writeLine($value->toArray());
            }
        );

        if (rewind($this->fileHandle) === false) {
            throw new ErrorException('Unable to rewind file handle!');
        }

        $csvData = fread($this->fileHandle, $this->fileSize);
        if ($csvData === false) {
            throw new ErrorException('Unable to read from file handle!');
        }

        if (fclose($this->fileHandle) === false) {
            throw new ErrorException('Unable to close file handle!');
        }

        return $csvData;
    }
}
