<?php

namespace PUGX\Shortid\Doctrine\Test;

use PHPUnit_Framework_TestCase as TestCase;
use PUGX\Shortid\Doctrine\ShortidType;

class ShortidTypeTest extends TestCase
{
    private $platform;
    private $type;

    protected function setUp()
    {
        $this->platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')->getMock();
        if (!ShortidType::hasType('shortid')) {
            ShortidType::addType('shortid', 'PUGX\Shortid\Doctrine\ShortidType');
        }
        $this->type = ShortidType::getType('shortid');
    }

    public function testgetSQLDeclaration()
    {
        $declaration = $this->type->getSQLDeclaration([], $this->platform);
        $this->assertNotEmpty($declaration);
    }

    public function testConvertToPHPValueNull()
    {
        $converted = $this->type->convertToPHPValue(null, $this->platform);
        $this->assertNull($converted);
    }

    public function testConvertToPHPValueShortid()
    {
        $converted = $this->type->convertToPHPValue('shortid', $this->platform);
        $this->assertInstanceOf('PUGX\Shortid\Shortid', $converted);
    }

    /**
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testConvertToPHPValueException()
    {
        $this->type->convertToPHPValue(3, $this->platform);
    }

    public function testConvertToDatabaseValueNull()
    {
        $converted = $this->type->convertToDatabaseValue(null, $this->platform);
        $this->assertNull($converted);
    }

    public function testConvertToDatabaseValue()
    {
        $shortid = $this->getMockBuilder('PUGX\Shortid\Shortid')->getMock();
        $converted = $this->type->convertToDatabaseValue($shortid, $this->platform);
        $this->assertInstanceOf('PUGX\Shortid\Shortid', $converted);
    }

    /**
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testConvertToDatabaseValueException()
    {
        $converted = $this->type->convertToDatabaseValue(42, $this->platform);
    }

    public function testGetName()
    {
        $name = $this->type->getName();
        $this->assertEquals('shortid', $name);
    }

    public function testRequiresSQLCommentHint()
    {
        $bool = $this->type->requiresSQLCommentHint($this->platform);
        $this->assertTrue($bool);
    }
}
