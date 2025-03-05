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

    public function writeExifToFile(Exif $exif, string $file): void
    {
        $encoding = '';
        if (count($this->encoding) > 0) {
            $encoding = '-charset ';
            foreach ($this->encoding as $key => $value) {
                $encoding .= escapeshellarg($key) . '=' . escapeshellarg($value);
            }
        }
        /**
         * @var \PHPExif\Mapper\Writer\Exiftool
         */
        $mapper = $this->getMapper();
        $mapper->setNumeric($this->numeric);

        $extractor = $this->getExtractor();
        $data = $extractor->extract($exif, $mapper->getMap());

        $rawData = $mapper->mapRawData($data);

        $command = $this->getExifWriteCommand($rawData, $file, $encoding);

        $result = $this->getCliOutput($command);
    }

    private function getExifWriteCommand(array $rawData, string $file, string $encoding): string
    {
        $command = [];
        $command[] = $this->toolPath;
        $command[] = ' ';
        foreach ($rawData as $key => $value) {
            $command[] = "-if";
            $command[] = " ";
            $command[] = '"$' . $key . '"';
            $command[] = " ";
            $command[] = "-$key=" . '"' . $value . '"';
            $command[] = " ";
        }

        $command[] = "-overwrite_original";
        $command[] = " ";
        $command[] = $encoding;
        $command[] = " ";
        $command[] = escapeshellarg($file);

        return implode('', $command);
    }
}
