<?php

namespace App\Export\Spout;

use App\AbstractReportModel;
use App\Export\ExcelExporterInterface;

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

use Illuminate\Database\Eloquent\Collection;

use DateTime;
use Exception;

class SpoutExcelExporter implements ExcelExporterInterface
{
    /** @var string */
    private $fileName;

    /** @var \Illuminate\Database\Eloquent\Collection */
    private $exportData;
    /**
     * SpoutExcelExporter constructor.
     * @param \Illuminate\Database\Eloquent\Collection $exportData
     */
    public function __construct(Collection $exportData)
    {
        $this->exportData = $exportData;
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

            $fieldNames = array_keys($this->exportData->first()->getAttributes());
            foreach ($fieldNames as $key => $fieldName) {
                $fieldNames[$key] =  @trans('language.' . str_slug($fieldName, '_'));
            }
            $writer->addRow($fieldNames);
            $collections = $this->exportData->chunk(1000);
            foreach ($collections as $collection) {
                foreach ($collection as $value) {
                    $writer->addRow($value->toArray());
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
