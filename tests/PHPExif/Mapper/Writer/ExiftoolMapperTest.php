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
        $dateValue = date('YYYY-MM-DD HH:MM:SS');
        $inputData = array(
            Exif::CREATION_DATE => $dateValue
        );

        $expectedData = array(
            Exiftool::DATETIMEORIGINAL => $dateValue,
            Exiftool::DATETIMEORIGINAL_QUICKTIME => $dateValue,
            Exiftool::DATETIMEORIGINAL_AVI => $dateValue,
            Exiftool::DATETIMEORIGINAL_WEBM => $dateValue,
            Exiftool::DATETIMEORIGINAL_OGG => $dateValue,
            Exiftool::DATETIMEORIGINAL_WMV => $dateValue,
            Exiftool::DATETIMEORIGINAL_APPLE => $dateValue,
            Exiftool::DATETIMEORIGINAL_PNG => $dateValue
        );

        $mappedData = $this->mapper->mapRawData($inputData);

        $this->assertEquals($expectedData, $mappedData);
    }
}
