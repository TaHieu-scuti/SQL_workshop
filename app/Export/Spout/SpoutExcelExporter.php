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
    /** @var \App\AbstractReportModel */
    private $model;

    /** @var string */
    private $fileName;

    /**
     * SpoutExcelExporter constructor.
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
            . '.xlsx';
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
     * @return string
     * @throws SpoutException
     */
    public function export($sessionKey)
    {
        try {
            $this->generateFilename();

            $tempFileName = tempnam(sys_get_temp_dir(), 'excel_export_');
            if ($tempFileName === false) {
                throw new SpoutException('Unable to create temporary file!');
            }

            $writer = WriterFactory::create(Type::XLSX)
                ->openToFile($tempFileName);

            $fieldNames = session($sessionKey.'fieldName');
            $writer->addRow($fieldNames);
            $exportData = $this->getDataToExport($sessionKey);
            $collections = $exportData->chunk(1000);
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
