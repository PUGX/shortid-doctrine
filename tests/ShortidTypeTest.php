<?php

namespace PUGX\Shortid\Doctrine\Test;

use PHPUnit\Framework\TestCase;
use PUGX\Shortid\Doctrine\ShortidType;

final class ShortidTypeTest extends TestCase
{
    private $platform;

    private $type;

    protected function setUp(): void
    {
        $this->platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')->getMock();
        if (!ShortidType::hasType('shortid')) {
            ShortidType::addType('shortid', 'PUGX\Shortid\Doctrine\ShortidType');
        }
        $this->type = ShortidType::getType('shortid');
    }

    public function testgetSQLDeclaration(): void
    {
        $declaration = $this->type->getSQLDeclaration([], $this->platform);
        $this->assertNotEmpty($declaration);
    }

    public function testConvertToPHPValueNull(): void
    {
        $converted = $this->type->convertToPHPValue(null, $this->platform);
        $this->assertNull($converted);
    }

    public function testConvertToPHPValueShortid(): void
    {
        $converted = $this->type->convertToPHPValue('shortid', $this->platform);
        $this->assertInstanceOf('PUGX\Shortid\Shortid', $converted);
    }

    public function testConvertToPHPValueException(): void
    {
        $this->expectException(\Doctrine\DBAL\Types\ConversionException::class);

        $this->type->convertToPHPValue(3, $this->platform);
    }

    public function testConvertToDatabaseValueNull(): void
    {
        $converted = $this->type->convertToDatabaseValue(null, $this->platform);
        $this->assertNull($converted);
    }

    public function testConvertToDatabaseValue(): void
    {
        $shortid = $this->getMockBuilder('PUGX\Shortid\Shortid')->disableOriginalConstructor()->getMock();
        $converted = $this->type->convertToDatabaseValue($shortid, $this->platform);
        $this->assertInstanceOf('PUGX\Shortid\Shortid', $converted);
    }

    public function testConvertToDatabaseValueException(): void
    {
        $this->expectException(\Doctrine\DBAL\Types\ConversionException::class);

        $converted = $this->type->convertToDatabaseValue(42, $this->platform);
    }

    public function testGetName(): void
    {
        $name = $this->type->getName();
        $this->assertEquals('shortid', $name);
    }

    public function testRequiresSQLCommentHint(): void
    {
        $bool = $this->type->requiresSQLCommentHint($this->platform);
        $this->assertTrue($bool);
    }
}
