<?php

/**
 * @codeCoverageIgnore
 */

namespace PHPExif\Contracts\Writer;

use PHPExif\Exif;
use PHPExif\Writer\PhpExifWriterException;

/**
 * PHP Exif Writer Adapter
 *
 * Defines the interface for writer adapters
 * @category   PHPExif
 * @package    Writer
 */
interface AdapterInterface
{
    /**
     * Writes the EXIF data to the given file
     *
     * @param Exif $exif The EXIF data to be written
     * @param string $file The file path to write the EXIF data to
     * @param array Optional $exifProps containting EXIF properties to be written to the file
     * @throws PhpExifWriterException If the EXIF data could not be written
     */
    public function writeExifToFile(Exif $exif, string $file, array $exifProps = []): void;

    /**
     * Writes the EXIF data contained within associative array $data to the given file
     * @param array $data containing {@see \PHPExif\Exif} properties as keys and value for each property
     * @param string $file the file path to write the EXIF data to
     * @return void
     * @throws PhpExifWriterException If the EXIF data could not be written
     */
    public function writeExifDataToFile(array $data, string $file): void;
}
