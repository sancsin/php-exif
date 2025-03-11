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
            ' -m -overwrite_original -if "defined \${ExifIFD:DateTimeOriginal}" -ExifIFD:DateTimeOriginal="2020:01:01 12:00:00" -if "defined \${ExifIFD:ISO}" -ExifIFD:ISO="100"  ' .
            escapeshellarg('/Users/sanchitsingh/source/forks/php-exif/tests/files/morning_glory_pool_500.jpg');

        $result = $reflectionMethod->invoke($this->adapter, $input, $file);
        $this->assertEquals($expected, $result);
    }

    /**
     * @group exiftool
     */
    public function testExifWriteToFile()
    {
        $file = PHPEXIF_TEST_ROOT . '/files/morning_glory_pool_500.jpg';
        $tempFile = PHPEXIF_TEST_ROOT . '/files/temp.jpg';

        if (!copy($file, $tempFile)) {
            $this->fail('Could not copy file');
            return;
        }

        try {
            $exif = new \PHPExif\Exif();
            $exif->setCreationDate(new \DateTime('2020-01-01 12:00:00'));
            $exif->setKeywords(['foo', 'bar']);

            $this->adapter->setOptions(array('encoding' => array('iptc' => 'cp1252')));
            $this->adapter->writeExifToFile($exif, $tempFile);

            $readerAdapter = new \PHPExif\Adapter\Reader\Exiftool();
            $readerAdapter->setOptions(array('encoding' => array('iptc' => 'cp1252')));
            $result = $readerAdapter->getExifFromFile($tempFile);

            $this->assertEquals($exif->getCreationDate(), $result->getCreationDate());
            $this->assertEqualsCanonicalizing($exif->getKeywords(), $result->getKeywords());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            if (file_exists($tempFile)) {
                if (!unlink($tempFile)) {
                    $this->fail('Could not delete temp file');
                }
            } else {
                $this->fail('Temp file does not exist');
            }
        }
    }
}
