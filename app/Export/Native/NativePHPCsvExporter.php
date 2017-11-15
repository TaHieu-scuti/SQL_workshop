<?php

namespace App\Export\Native;

use App\Export\CSVExporterInterface;
use App\Export\Native\Exceptions\CsvException;
use Illuminate\Database\Eloquent\Collection;

use DateTime;

class NativePHPCsvExporter implements CSVExporterInterface
{
    /**
     * @var string 
     */
    private $fileName;

    /**
     * @var resource 
     */
    private $fileHandle;

    /**
     * @var int 
     */
    private $fileSize = 0;

    /**
     * @var \Illuminate\Database\Eloquent\Collection 
     */
    private $exportData;

    /** @var string[] */
    private $fieldNames;

    /**
     * NativePHPCsvExporter constructor.
     *
     * @param \Illuminate\Database\Eloquent\Collection $exportData
     * @param string[] $fieldNames
     */
    public function __construct(Collection $exportData, array $fieldNames = null)
    {
        $this->exportData = $exportData;
        $this->fieldNames = $fieldNames;
    }

    private function generateFilename()
    {
        // get table name
        $tableName = $this->exportData->first()->getTable();

        $this->fileName = (new DateTime)->format("Y_m_d h_i ")
            . "{$tableName}"
            . '.csv';
    }

    /**
     * @throws CsvException
     */
    private function writeBOM()
    {
        $bytesWritten = fputs($this->fileHandle, chr(0xEF) . chr(0xBB) . chr(0xBF));
        if ($bytesWritten === false) {
            throw new CsvException('Failed to write Byte Order Mark!');
        }

        $this->fileSize += $bytesWritten;
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
    public function export()
    {
        $this->generateFilename();
        $this->fileHandle = tmpfile();
        if ($this->fileHandle === false) {
            throw new CsvException('Unable to open temporary file!');
        }

        $this->writeBOM();

        // get fields' names
        $fieldNames = $this->fieldNames;
        if ($fieldNames === null) {
            $fieldNames = array_keys($this->exportData->first()->getAttributes());
        }

        $this->writeLine($fieldNames);
        $this->exportData->each(
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
