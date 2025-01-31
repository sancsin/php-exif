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
     * @param array $data
     * @return void
     */
    public function extract($object, array $data): void;
}
