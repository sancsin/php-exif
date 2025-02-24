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
        $allKeysForSameValues = $this->getAllKeysForSameValues($this->map);

        foreach ($data as $dataKey => $dataValue) {
            if (!array_key_exists($dataKey, $allKeysForSameValues)) {
                continue;
            }

            foreach ($allKeysForSameValues[$dataKey] as $value) {
                $mappedData[$value] = $dataValue;
            }
        }

        return $mappedData;
    }

    private function getAllKeysForSameValues(array $inputArray): array
    {
        $result = [];
        foreach ($inputArray as $key => $value) {
            $result[$value][] = $key;
        }

        return $result;
    }
}
