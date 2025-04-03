<?php

namespace PHPExif\Adapter\Writer;

use PHPExif\Exif;
use PHPExif\Mapper\Writer\Exiftool as MapperExiftool;
use PHPExif\Adapter\ExiftoolTrait;
use PHPExif\PhpExifException;
use Safe\DateTime;

/**
 * PHP Exif Exiftool Writer Adapter
 *
 * Uses native PHP functionality to write data to a file
 *
 * @category    PHPExif
 * @package     Writer
 */

class Exiftool extends AbstractAdapter
{
    use ExiftoolTrait;

    protected string $mapperClass = MapperExiftool::class;

    /**
     * Set up Exiftool writer adapter
     * @param array $options option to be passed to the parent
     * @param string $path optional path to the tool
     * @return self
     */
    public function __construct(array $options = [], string $path = '')
    {
        parent::__construct($options);
        $this->toolPath = $path;
    }

    /**
     * Writes the EXIF data to given file by reading from
     * an EXIF object
     *
     * @param Exif $exif the EXIF object to read EXIF data from
     * @param string $file the image/video file to write EXIF data to
     * @return string returns the output of the command
     * @throws PhpExifException
     */
    public function writeExifToFile(Exif $exif, string $file): string
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

        // This line will throw exception if the command fails
        $result = $this->getCliOutput($command);

        return $result;
    }

    /**
     * Generates the command to be executed using the tool
     * based on the data to be written to the file
     *
     * @param array $data array of data to be written to the file
     * @param string $file the file path
     * @param string $encoding encoding to be used by the tool
     * @return string the command to be executed
     */
    private function getExifWriteCommand(array $data, string $file, string $encoding = ""): string
    {
        $command = [];
        $command[] = $this->toolPath;
        $command[] = ' ';
        $command[] = '-m';
        $command[] = ' ';
        $command[] = '-overwrite_original';
        $command[] = ' ';
        foreach ($data as $key => $value) {
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
