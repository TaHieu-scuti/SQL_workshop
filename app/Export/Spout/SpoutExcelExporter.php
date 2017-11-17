<?php

namespace App\Export\Spout;

use App\Export\ExcelExporterInterface;

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

use Illuminate\Database\Eloquent\Collection;

use DateTime;
use Exception;

class SpoutExcelExporter implements ExcelExporterInterface
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $exportData;

    /** @var string[] */
    private $fieldNames;

    /** @var string[] */
    private $aliases;

    /**
     * SpoutExcelExporter constructor.
     *
     * @param \Illuminate\Database\Eloquent\Collection $exportData
     * @param string[] $fieldNames Optional fieldNames to export.
     *                             When set only the fields of this array will be exported,
     *                             even if the models in the collection have other values as well.
     * @param string[] $aliases    Optional aliases for the fieldNames, when passed these names will be used instead of
     *                             the actual field/column names.
     */
    public function __construct(
        Collection $exportData,
        array $fieldNames = null,
        array $aliases = null
    ) {
        $this->exportData = $exportData;
        $this->fieldNames = $fieldNames;
        $this->aliases = $aliases;
    }

    private function generateFilename()
    {
        // get table name
        $tableName = $this->exportData->first()->getTable();

        $this->fileName = (new DateTime)->format("Y_m_d h_i ")
            . "{$tableName}"
            . '.xlsx';
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
     * @throws SpoutException
     */
    public function export()
    {
        try {
            $this->generateFilename();

            $tempFileName = tempnam(sys_get_temp_dir(), 'excel_export_');
            if ($tempFileName === false) {
                throw new SpoutException('Unable to create temporary file!');
            }

            $writer = WriterFactory::create(Type::XLSX)
                ->openToFile($tempFileName);

            $fieldNames = $this->fieldNames;
            if ($fieldNames === null) {
                $fieldNames = array_keys($this->exportData->first()->getAttributes());
            }

            if ($this->aliases === null) {
                $writer->addRow($fieldNames);
            } else {
                $writer->addRow($this->aliases);
            }

            $collections = $this->exportData->chunk(1000);
            foreach ($collections as $collection) {
                foreach ($collection as $value) {
                    $array = [];
                    foreach ($fieldNames as $fieldName) {
                        $array[$fieldName] = $value->$fieldName;
                    }
                    $writer->addRow($array);
                }
            }

            $writer->close();

            $excelData = file_get_contents($tempFileName);
            if ($excelData === false) {
                throw new SpoutException('Unable to read the temporary file!');
            }

            return $excelData;
        } catch (SpoutException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw new SpoutException(
                'An error occurred within the Spout library!',
                0,
                $exception
            );
        }
    }
}
