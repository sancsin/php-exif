<?php

namespace PHPExif\Mapper\Writer;

use PHPExif\Contracts\MapperInterface;
use PHPExif\Mapper\Writer\Exiftool;
use PHPExif\Exif;

class ExiftoolMapperTest extends \PHPUnit\Framework\TestCase
{
    protected $mapper;

    public function setUp(): void
    {
        $this->mapper = new Exiftool();
    }

    public function testClassImplementsCorrectInterface()
    {
        $this->assertInstanceOf(MapperInterface::class, $this->mapper);
    }

    public function testGetAllKeysForSameValuesReturnsCorrectArray()
    {
        $reflectionMethod = new \ReflectionMethod(get_class($this->mapper), 'getAllKeysForSameValues');
        $reflectionMethod->setAccessible(true);

        $inputArray = array('foo' => 'bar', 'baz' => 'bar');
        $expectedArray = array('bar' => array('foo', 'baz'));
        $result = $reflectionMethod->invoke($this->mapper, $inputArray);

        $this->assertEquals($expectedArray, $result);
    }

    public function testMapRawDataIgnoresFieldIfItDoesntExist()
    {
        $rawData = array('foo' => 'bar');
        $mapped = $this->mapper->mapRawData($rawData);

        $this->assertCount(0, $mapped);
    }

    public function testMapRawDataMapsAllValues()
    {
        $inputData = array(
            Exif::CREATION_DATE => '2022-05-03 13:45:13'
        );

        $expectedData = array(
            Exiftool::DATETIMEORIGINAL => '2022-05-03 13:45:13',
            Exiftool::DATETIMEORIGINAL_QUICKTIME => '2022-05-03 13:45:13',
            Exiftool::DATETIMEORIGINAL_AVI => '2022-05-03 13:45:13',
            Exiftool::DATETIMEORIGINAL_WEBM => '2022-05-03 13:45:13',
            Exiftool::DATETIMEORIGINAL_OGG => '2022-05-03 13:45:13',
            Exiftool::DATETIMEORIGINAL_WMV => '2022-05-03 13:45:13',
            Exiftool::DATETIMEORIGINAL_APPLE => '2022-05-03 13:45:13',
            Exiftool::DATETIMEORIGINAL_PNG => '2022-05-03 13:45:13'
        );

        $mappedData = $this->mapper->mapRawData($inputData);

        $this->assertEquals($expectedData, $mappedData);
    }
}
