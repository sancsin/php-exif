<?php

namespace PHPExif\Writer;

use PHPExif\Adapter\Exiftool as ExiftoolAdapter;
use PHPExif\Contracts\AdapterInterface;
use PHPExif\Contracts\WriterInterface;
use PHPExif\Enum\ReaderType;
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

    public function write(Exif $exif, string $file): void
    {
        return $this->adapter->writeExifToFile($exif, $file);
    }
}
