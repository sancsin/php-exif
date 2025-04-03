<?php

/**
 * @codeCoverageIgnore
 */

namespace PHPExif\Contracts\Reader;

use PHPExif\Exif;
use PHPExif\PhpExifException;

/**
 * PHP Exif Reader Adapter
 *
 * Defines the interface for reader adapters
 *
 * @category    PHPExif
 * @package     Reader
 */
interface AdapterInterface
{
    /**
     * Reads & parses the EXIF data from given file
     *
     * @param string $file
     * @return Exif Instance of Exif object with data
     * @throws PhpExifException If the EXIF data could not be read
     */
    public function getExifFromFile(string $file): Exif;
}
