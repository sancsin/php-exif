<?php

/**
 * @codeCoverageIgnore
 */

namespace PHPExif\Contracts;

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
     * @return void
     */
    public function write(Exif $exif, string $file): void;
}
