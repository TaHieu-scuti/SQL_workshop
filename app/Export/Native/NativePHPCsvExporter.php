<?php

namespace App\Export\Native;

use App\AbstractReportModel;
use App\Export\CSVExporterInterface;
use App\Export\Native\Exceptions\CsvException;

use DateTime;

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

        $this->fileName = (new DateTime)->format("Y_m_d h_i ")
            . "{$tableName}"
            . '.csv';
    }

    /**
     * @param array $data
     * @throws CsvException
     */
    private function writeLine(array $data)
    {
        $bytesWritten = fputcsv($this->fileHandle, $data);
        if ($bytesWritten === false) {
            throw new CsvException('Failed to write line to csv!');
        }

        $this->fileSize += $bytesWritten;
    }

    private function getDataToExport($sessionKey)
    {
        return $this->model->getDataForExport(
            session($sessionKey.'fieldName'),
            session($sessionKey.'accountStatus'),
            session($sessionKey.'startDay'),
            session($sessionKey.'endDay'),
            session($sessionKey.'columnSort'),
            session($sessionKey.'sort')
        );
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return bool|string
     * @throws CsvException
     */
    public function export($sessionKey)
    {
        $this->generateFilename();

        $this->fileHandle = tmpfile();
        if ($this->fileHandle === false) {
            throw new CsvException('Unable to open temporary file!');
        }

        // get fields' names
        $fieldNames = session($sessionKey.'fieldName');
        $this->writeLine($fieldNames);
        $data = $this->getDataToExport($sessionKey);
        $data->each(
            function ($value) {
                $this->writeLine($value->toArray());
            }
        );

        if (rewind($this->fileHandle) === false) {
            throw new CsvException('Unable to rewind file handle!');
        }

        $csvData = fread($this->fileHandle, $this->fileSize);
        if ($csvData === false) {
            throw new CsvException('Unable to read from file handle!');
        }

        if (fclose($this->fileHandle) === false) {
            throw new CsvException('Unable to close file handle!');
        }
        return $csvData;
    }
}
