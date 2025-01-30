<?php

namespace PHPExif\Adapter;

use ForceUTF8\Encoding;

trait ConvertToUTF8Trait
{
    /**
     * Encodes an array of strings into UTF8
     *
     * @template T of array|string
     * @param T $data
     * @return (T is string ? string : array)
     */
    // @codeCoverageIgnoreStart
    // this is fine because we use it directly in our tests for Exiftool and Native
    public function convertToUTF8(array|string $data): array|string
    {
        if (is_array($data)) {
            /** @var array|string|null $v */
            foreach ($data as $k => $v) {
                if ($v !== null) {
                    $data[$k] = $this->convertToUTF8($v);
                }
            }
        } else {
            $data = Encoding::toUTF8($data);
        }
        return $data;
    }
}
