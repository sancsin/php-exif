<?php

namespace PHPExif\Adapter\Writer;

use PHPExif\Exif;
use PHPExif\Mapper\Writer\Exiftool as MapperExiftool;
use PHPExif\Adapter\ExiftoolTrait;

class Exiftool extends AbstractAdapter
{
    use ExiftoolTrait;

    protected string $mapperClass = MapperExiftool::class;

    public function __construct(array $options = [], string $path = '')
    {
        parent::__construct($options);
        $this->toolPath = $path;
    }

    public function writeExifToFile(Exif $exif, string $file): void {}
}
