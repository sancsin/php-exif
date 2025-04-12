<?php

namespace PHPExif\Writer;

use PHPExif\Adapter\Writer\Exiftool as ExiftoolAdapter;
use PHPExif\Contracts\Writer\AdapterInterface;
use PHPExif\Contracts\Writer\WriterInterface;
use PHPExif\Enum\WriterType;
use PHPExif\Exif;

class Writer implements WriterInterface
{

    public function __construct(protected readonly AdapterInterface $adapter) {}

    public static function factory(WriterType $type, string $path = ''): Writer
    {
        $adapter = match ($type) {
            WriterType::EXIFTOOL => new ExiftoolAdapter(path: $path)
        };

        return new Writer($adapter);
    }

    public function write(Exif $exif, array $exifProps = [], string $file): void
    {
        $this->adapter->writeExifToFile($exif, $exifProps, $file);
    }
}
