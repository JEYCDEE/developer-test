<?php

namespace App\Services;

use App\Contracts\BaseConverterInterface;
use App\Enums\ConvertTypesEnum;
use App\Exceptions\FileConverterException;

/**
 * Class FileConverter
 * @package App\Services
 */
class FileConverter
{
    /**
     * @param string $fileType
     * @param array $data
     * @return BaseConverterInterface
     * @throws FileConverterException
     */
    public function get(string $fileType, array $data): BaseConverterInterface
    {
        if (empty($data)) {
            throw new FileConverterException('Unable to convert file without data');
        }

        switch ($fileType) {
            case ConvertTypesEnum::CSV_TYPE:
                return new CsvConverter($data);
            default:
                throw new FileConverterException('Invalid file type');
        }
    }
}
