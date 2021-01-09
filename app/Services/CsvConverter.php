<?php

namespace App\Services;

use App\Contracts\BaseConverterInterface;
use Exception;

/**
 * Class CsvConverter
 * @package App\Services
 */
class CsvConverter implements BaseConverterInterface
{
    public const DELIMITER = ',';

    private array $data;

    /**
     * CsvConverter constructor.
     * @param $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function convert($additionalParams = null): string
    {
        $columnNames = $this->getColumnNames($this->data);
        $rowsData = $this->getRowsData($this->data);
        $storagePath = config('storage.convertedFiles.csv');
        $fileName = date('Ymd-His', time()) . '-' . rand(1000, 9999) . '.csv';
        $filePath = "./{$storagePath}/{$fileName}";

        try {
            $file = fopen($filePath, 'wb');

            fputcsv($file, $columnNames);

            foreach ($rowsData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);

            return asset($filePath);
        } catch (Exception $e) {
            unlink($filePath);

            throw $e;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function getColumnNames(array $data): array
    {
        return array_keys($this->data[0]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function getRowsData(array $data): array
    {
        $rowsData = [];

        foreach ($data as $row) {
            $rowsData[] = array_values($row);
        }

        return $rowsData;
    }
}
