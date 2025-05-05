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

    public function writeExifDataToFile(array $data, string $file): void
    {
        $encoding = $this->getEncoding();
        /**
         * @var \PHPExif\Mapper\Writer\Exiftool
         */
        $mapper = $this->getMapper();
        $mapper->setNumeric($this->numeric);

        $exifData = $mapper->mapRawData($data);

        if (count($exifData) == 0) {
            throw new PhpExifException('No data to write');
        }

        $command = $this->getExifWriteCommand($exifData, $file, $encoding);

        // This line will throw exception if the command fails
        $this->getCliOutput($command);
    }

    /**
     * Writes the EXIF data to given file by reading from
     * an EXIF object
     *
     * @param Exif $exif the EXIF object to read EXIF data from
     * @param string $file the image/video file to write EXIF data to
     * @param array Optional $exifProps containting EXIF properties to be written to the files
     * @throws PhpExifException
     */
    public function writeExifToFile(Exif $exif, string $file, array $exifProps = []): void
    {
        $encoding = $this->getEncoding();
        /**
         * @var \PHPExif\Mapper\Writer\Exiftool
         */
        $mapper = $this->getMapper();
        $mapper->setNumeric($this->numeric);

        $extractor = $this->getExtractor();
        $data = $extractor->extract($exif, $mapper->getMap(), $exifProps);

        $command = $this->getExifWriteCommand($data, $file, $encoding);

        // This line will throw exception if the command fails
        $this->getCliOutput($command);
    }

    private function getEncoding()
    {
        $encoding = '';
        if (count($this->encoding) > 0) {
            $encoding = '-charset ';
            foreach ($this->encoding as $key => $value) {
                $encoding .= escapeshellarg($key) . '=' . escapeshellarg($value);
            }
        }

        return $encoding;
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
