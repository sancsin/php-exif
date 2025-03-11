<?php

/**
 * @codeCoverageIgnore
 */

namespace PHPExif\Contracts\Writer;

use PHPExif\Exif;
use PHPExif\Writer\PhpExifWriterException;

interface AdapterInterface
{
    /**
     * Writes the EXIF data to the given file
     *
     * @param Exif $exif
     * @param string $file
     * @throws PhpExifWriterException If the EXIF data could not be written
     */
    public function writeExifToFile(Exif $exif, string $file): string|false;
}
