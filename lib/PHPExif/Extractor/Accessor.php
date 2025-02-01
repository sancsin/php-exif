<?php

namespace PHPExif\Extractor;

use PHPExif\Contracts\Writer\ExtractorInterface;

class Accessor implements ExtractorInterface
{

    public function extract($object, array $map): array
    {
        $result = [];

        foreach ($map as $property => $method) {

            $accessor = $this->determineAccessor($method);

            if (method_exists($object, $accessor)) {
                $value = $object->$accessor(); // @phpstan-ignore-line, PhpStan does not like variadic calls
                if ($value !== null && $value !== '' && $value != false) {
                    $result[$property] = $value;
                }
            }
        }

        return $result;
    }

    protected function determineAccessor(string $property): string
    {
        $method = 'get' . ucfirst($property);
        return $method;
    }
}
