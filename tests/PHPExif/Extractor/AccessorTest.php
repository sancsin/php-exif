<?php

use PHPExif\Extractor\Accessor;

class AccessorTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void {}

    public function testAccessorCallsDetermineAccessor()
    {
        $input =  array(
            'foo' => 'bar',
        );

        /** @var Accessor|\PHPUnit\Framework\MockObject\MockObject $mock */
        $mock = $this->getMockBuilder(Accessor::class)
            ->onlyMethods(array('determineAccessor'))
            ->getMock();

        $mock->expects($this->exactly(count($input)))
            ->method('determineAccessor')
            ->will($this->returnValue('getFoo'));

        $object = new AccessorTestClass();

        $mock->extract($object, $input);
    }

    public function testExtractCallsAccessorOnObject()
    {
        $inputs = array(
            'faz' => 'foo',
            'baz' => 'bar'
        );

        $expected = array(
            'faz' => 'faz value',
            'baz' => 'baz value'
        );

        $mock = $this->getMockBuilder(AccessorTestClass::class)
            ->onlyMethods(array('getFoo', 'getBar'))
            ->getMock();

        $mock->expects($this->once())
            ->method('getFoo')
            ->willReturn($expected['faz']);

        $mock->expects($this->once())
            ->method('getBar')
            ->willReturn($expected['baz']);

        $accessor = new Accessor();
        $accessor->extract($mock, $inputs);
    }

    public function testExtractResultEmptyArray()
    {
        $inputs = array(
            'faz' => 'food'
        );

        $expected = [];

        $mock = $this->getMockBuilder(AccessorTestClass::class)
            ->onlyMethods(array('getFoo', 'getBar'))
            ->getMock();
        $mock->method('getFoo')->willReturn('FooValue');
        $mock->method('getBar')->willReturn('BarValue');

        $accessor = new Accessor();
        $result = $accessor->extract($mock, $inputs);

        $this->assertEquals($result, $expected);
    }

    public function testExtractReturnArray()
    {
        $inputs = array(
            'faz' => 'foo',
            'baz' => 'bar'
        );

        $expected = array(
            'faz' => 'faz value',
            'baz' => 'baz value'
        );

        $mock = $this->getMockBuilder(AccessorTestClass::class)
            ->onlyMethods(array('getFoo', 'getBar'))
            ->getMock();

        $mock->method('getFoo')->willReturn($expected['faz']);
        $mock->method('getBar')->willReturn($expected['baz']);

        $accessor = new Accessor();
        $result = $accessor->extract($mock, $inputs);

        $this->assertEquals($expected, $result);
    }

    public function testExtractReturnArrayWithIgnoredValues()
    {
        $inputs = array(
            'faz' => 'foo',
            'baz' => 'bar'
        );

        $expected = array(
            'faz' => 'faz value'
        );

        $mock = $this->getMockBuilder(AccessorTestClass::class)
            ->onlyMethods(array('getFoo', 'getBar'))
            ->getMock();


        // Test with null value

        $mock->method('getFoo')->willReturn($expected['faz']);
        $mock->method('getBar')->willReturn(null);

        $accessor = new Accessor();
        $result = $accessor->extract($mock, $inputs);

        $this->assertEquals($expected, $result);

        // Test with empty string

        $mock->method('getBar')->willReturn('');

        $result = $accessor->extract($mock, $inputs);

        $this->assertEquals($expected, $result);

        // Test with false

        $mock->method('getBar')->willReturn(false);

        $result = $accessor->extract($mock, $inputs);

        $this->assertEquals($expected, $result);
    }

    public function testFilterMap()
    {
        $inputs = array(
            'faz' => 'foo',
            'baz' => 'bar',
            'gaz' => 'gar'
        );

        $expected = array(
            'faz' => 'faz value',
            'baz' => 'baz value'
        );

        $filterMap = array('foo', 'bar');

        $mock = $this->getMockBuilder(AccessorTestClass::class)
            ->onlyMethods(array('getFoo', 'getBar', 'getGaz'))
            ->getMock();

        $mock->expects($this->once())
            ->method('getFoo')
            ->willReturn($expected['faz']);

        $mock->expects($this->once())
            ->method('getBar')
            ->willReturn($expected['baz']);

        $mock->expects($this->never())
            ->method('getGaz');

        $accessor = new Accessor();
        $result = $accessor->extract($mock, $inputs, $filterMap);
        $this->assertEquals($expected, $result);
    }
}

class AccessorTestClass
{
    public function getFoo() {}

    public function getBar() {}

    public function getGaz() {}
}
