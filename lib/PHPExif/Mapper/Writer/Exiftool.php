<?php

namespace PHPExif\Mapper\Writer;

use PHPExif\Mapper\ExiftoolTrait;
use PHPExif\Mapper\AbstractMapper;

class Exiftool extends AbstractMapper
{
    use ExiftoolTrait;

    public function mapRawData(array $data): array
    {
        $mappedData = [];
        $reverseMap = array_flip($this->map);

        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $reverseMap)) {
                continue;
            }

            $mappedData[$reverseMap[$field]] = $value;
        }

        return $mappedData;
    }
}
