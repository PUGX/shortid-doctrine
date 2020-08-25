<?php

namespace PUGX\Shortid\Doctrine\Test;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;
use PUGX\Shortid\Doctrine\ShortidType;
use PUGX\Shortid\Shortid;

final class ShortidTypeTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject&AbstractPlatform */
    private $platform;

    private $type;

    protected function setUp(): void
    {
        $this->platform = $this->getMockBuilder(AbstractPlatform::class)->getMock();
        if (!ShortidType::hasType('shortid')) {
            ShortidType::addType('shortid', ShortidType::class);
        }
        $this->type = ShortidType::getType('shortid');
    }

    public function testGetSQLDeclaration(): void
    {
        $this->platform->expects(self::once())->method('getVarcharTypeDeclarationSQL')->willReturn('CHAR');
        $declaration = $this->type->getSQLDeclaration([], $this->platform);
        self::assertNotEmpty($declaration);
    }

    public function testConvertToPHPValueNull(): void
    {
        $converted = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($converted);
    }

    public function testConvertToPHPValueShortid(): void
    {
        $converted = $this->type->convertToPHPValue('shortid', $this->platform);
        self::assertInstanceOf(Shortid::class, $converted);
    }

    public function testConvertToPHPValueException(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue(3, $this->platform);
    }

    public function testConvertToDatabaseValueNull(): void
    {
        $converted = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($converted);
    }

    public function testConvertToDatabaseValue(): void
    {
        $shortid = $this->getMockBuilder(Shortid::class)->disableOriginalConstructor()->getMock();
        $converted = $this->type->convertToDatabaseValue($shortid, $this->platform);
        self::assertInstanceOf(Shortid::class, $converted);
    }

    public function testConvertToDatabaseValueException(): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue(42, $this->platform);
    }

    public function testGetName(): void
    {
        $name = $this->type->getName();
        self::assertEquals('shortid', $name);
    }

    public function testRequiresSQLCommentHint(): void
    {
        $bool = $this->type->requiresSQLCommentHint($this->platform);
        self::assertTrue($bool);
    }
}
