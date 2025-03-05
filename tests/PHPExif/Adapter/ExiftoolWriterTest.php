<?php


use PHPExif\Adapter\Writer\Exiftool;

class ExiftoolWriterTest extends \PHPUnit\Framework\TestCase
{
    protected Exiftool $adapter;

    public function setUp(): void
    {
        $this->adapter = new Exiftool(path: escapeshellarg('exiftool'));
    }

    /**
     * @group exiftool
     */
    public function testGetExifWriteCommandString()
    {
        $file = PHPEXIF_TEST_ROOT . '/files/morning_glory_pool_500.jpg';
        $input = [
            'ExifIFD:DateTimeOriginal' => '2020:01:01 12:00:00',
            'ExifIFD:ISO' => '100'
        ];
        $reflectionMethod = new \ReflectionMethod(get_class($this->adapter), 'getExifWriteCommand');
        $reflectionMethod->setAccessible(true);

        $expected = escapeshellarg('exiftool') .
            ' -if "defined \${ExifIFD:DateTimeOriginal}" -ExifIFD:DateTimeOriginal="2020:01:01 12:00:00" -if "defined \${ExifIFD:ISO}" -ExifIFD:ISO="100"  -o temp.jpg ' .
            escapeshellarg('/Users/sanchitsingh/source/forks/php-exif/tests/files/morning_glory_pool_500.jpg');

        $result = $reflectionMethod->invoke($this->adapter, $input, $file);
        $this->assertEquals($expected, $result);
    }
}
