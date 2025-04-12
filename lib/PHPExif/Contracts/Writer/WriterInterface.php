<?php

/**
 * @codeCoverageIgnore
 */

namespace PHPExif\Contracts\Writer;

use PHPExif\Exif;

/**
 * PHP Exif Writer
 *
 * Defines interface for reader functionality
 *
 * @category   PHPExif
 * @package    Writer
 */
interface WriterInterface
{

    /**
     * Writes EXIF data to given file
     *
     * @param  \PHPExif\Exif Instance of Exif object with data
     * @param string $file
     * @param array $exifProps Optional array of EXIF properties to be written to the file
     * @return void
     */
    public function write(Exif $exif, array $exifProps, string $file): void;
}
