<?php

namespace PHPExif\Adapter\Writer;

use PHPExif\Exif;
use PHPExif\Mapper\Writer\Exiftool as MapperExiftool;
use PHPExif\Adapter\ExiftoolTrait;
use Safe\DateTime;

class Exiftool extends AbstractAdapter
{
    use ExiftoolTrait;

    protected string $mapperClass = MapperExiftool::class;

    public function __construct(array $options = [], string $path = '')
    {
        parent::__construct($options);
        $this->toolPath = $path;
    }

    public function writeExifToFile(Exif $exif, string $file): string|false
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

        $command = $this->getExifWriteCommand($data, $file, $encoding);

        $result = $this->getCliOutput($command);

        return $result;
    }

    private function getExifWriteCommand(array $rawData, string $file, string $encoding = ""): string
    {
        $command = [];
        $command[] = $this->toolPath;
        $command[] = ' ';
        $command[] = '-m';
        $command[] = ' ';
        $command[] = '-overwrite_original';
        $command[] = ' ';
        foreach ($rawData as $key => $value) {
            $is_array = false;
            if (is_object($value) && get_class($value) == 'DateTime') {
                $value = $value->format('Y-m-d H:i:sP');
            } else if (is_array($value)) {
                $value = implode(', ', $value);
                $is_array = true;
            }
            $command[] = '-if "defined';
            $command[] = " ";
            $command[] = '\${' . $key . '}"';
            $command[] = " ";
            if ($is_array) {
                $command[] = "-sep";
                $command[] = " ";
                $command[] = escapeshellarg(",");
                $command[] = " ";
            }
            $command[] = "-" . $key . "=" . '"' . $value . '"';
            $command[] = " ";
        }

        $command[] = $encoding;
        $command[] = " ";
        $command[] = escapeshellarg($file);

        return implode('', $command);
    }
}
