<?php

namespace App\Export\Native;

use App\Export\CSVExporterInterface;
use App\Export\Native\Exceptions\CsvException;
use Illuminate\Support\Collection;

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

    /** @var string[] */
    private $aliases;

    /** @var string */
    private $engine;

    /** @var string */
    private $reportType;

    /**
     * NativePHPCsvExporter constructor.
     *
     * @param \Illuminate\Database\Eloquent\Collection $exportData
     * @param string $engine Engine of report (YSS, YDN, ADW)
     * @param string $reportType Type of report
     * @param string[] $fieldNames Optional fieldNames to export.
     *                             When set only the fields of this array will be exported,
     *                             even if the models in the collection have other values as well.
     * @param string[] $aliases    Optional aliases for the fieldNames, when passed these names will be used instead of
     *                             the actual field/column names.
     */
    public function __construct(
        Collection $exportData,
        $engine,
        $reportType,
        array $fieldNames = null,
        array $aliases = null
    ) {
        $this->exportData = $exportData;
        $this->engine = $engine;
        $this->reportType = $reportType;
        $this->fieldNames = $fieldNames;
        $this->aliases = $aliases;
    }

    private function generateFilename()
    {
        // get table name
        if (is_array($this->exportData->first())) {
            $tableName = 'account_list';
        } else {
            $tableName = $this->engine.'-'.$this->reportType;
        }

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

        // get fields' names
        $fieldNames = $this->fieldNames;
        if ($fieldNames === null) {
            $fieldNames = array_keys($this->exportData->first()->getAttributes());
        }

        if ($this->aliases === null) {
            $this->writeLine($fieldNames);
        } else {
            foreach ($this->aliases as $key => $alias) {
                $this->aliases[$key] = mb_convert_encoding($alias, "Shift-JIS", "UTF-8");
            }
            $this->writeLine($this->aliases);
        }
        $this->exportData->each(
            function ($value) use ($fieldNames) {
                if (is_array($value)) {
                    $value = json_decode(json_encode($value));
                }
                $array = [];
                foreach ($fieldNames as $fieldName) {
                    $data = $value->$fieldName;
                    if (ctype_digit($data)) {
                        $array[$fieldName] = number_format($data, 0, '', ',');
                    } elseif ($fieldName === 'cost' && is_float($data)) {
                        $array[$fieldName] = number_format($data, 0, '', ',');
                    } elseif (is_float($data)) {
                        $array[$fieldName] = number_format($data, 2, '.', ',');
                    } else {
                        $array[$fieldName] = $data;
                    }
                }
                $this->writeLine($array);
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
