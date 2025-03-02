<?php

namespace PHPExif\Adapter\Reader;

use PHPExif\Exif;
use PHPExif\Mapper\Reader\Exiftool as MapperExiftool;
use PHPExif\Adapter\ExiftoolTrait;

use Safe\Exceptions\JsonException;
use PHPExif\Reader\PhpExifReaderException;

use function Safe\json_decode;

/**
 * PHP Exif Exiftool Reader Adapter
 *
 * Uses native PHP functionality to read data from a file
 *
 * @category    PHPExif
 * @package     Reader
 */
class Exiftool extends AbstractAdapter
{

    use ExiftoolTrait;

    protected string $mapperClass = MapperExiftool::class;

    /**
     * Set up Exiftool adapter
     *
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
     * Reads & parses the EXIF data from given file
     *
     * @param string $file
     * @return Exif Instance of Exif object with data
     * @throws PhpExifReaderException If the EXIF data could not be read
     */
    public function getExifFromFile(string $file): Exif
    {
        $encoding = '';
        if (count($this->encoding) > 0) {
            $encoding = '-charset ';
            foreach ($this->encoding as $key => $value) {
                $encoding .= escapeshellarg($key) . '=' . escapeshellarg($value);
            }
        }
        /**
         * @var string
         */
        $result = $this->getCliOutput(
            sprintf(
                '%1$s%3$s -j -a -G1 %5$s -c %4$s %2$s',
                $this->getToolPath(),
                escapeshellarg($file),
                $this->numeric ? ' -n' : '',
                escapeshellarg('%d deg %d\' %.4f"'),
                $encoding
            )
        );

        /**
         * @var string $result
         */
        $result = $this->convertToUTF8($result);

        try {
            $data = json_decode($result, true);
        } catch (JsonException $e) {
            // @codeCoverageIgnoreStart
            $data = false;
            // @codeCoverageIgnoreStart
        }
        if (!is_array($data)) {
            // @codeCoverageIgnoreStart
            throw new PhpExifReaderException(
                'Could not decode exiftool output'
            );
            // @codeCoverageIgnoreEnd
        }

        // map the data:
        /**
         * @var \PHPExif\Mapper\Reader\Exiftool
         */
        $mapper = $this->getMapper();
        $mapper->setNumeric($this->numeric);
        $mappedData = $mapper->mapRawData(reset($data));

        // hydrate a new Exif object
        $exif = new Exif();
        $hydrator = $this->getHydrator();
        $hydrator->hydrate($exif, $mappedData);
        $exif->setRawData(reset($data));

        return $exif;
    }
}
