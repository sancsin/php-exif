<?php

/**
 * @codeCoverageIgnore
 */

namespace PHPExif\Contracts\Writer;

/**
 * PHP Exif Extractor
 *
 * Defines the interface for an extractor
 * @package PHPExif\Contracts\Writer
 */

interface ExtractorInterface
{
    /**
     * Extracts given Exif object into an array of data
     * @param mixed $object
     * @param array $map
     * @param array $mapFilter An optional array of properties to filter
     * @return array
     */
    public function extract($object, array $map, array $mapFilter = []): array;
}
