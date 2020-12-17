<?php

namespace PUGX\Shortid\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PUGX\Shortid\Shortid;

/**
 * Field type mapping for the Doctrine Database Abstraction Layer (DBAL).
 *
 * ShortId fields will be stored as a string in the database and converted back to
 * the ShortId value object when querying.
 */
final class ShortidType extends Type
{
    public const NAME = 'shortid';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $length = $column['length'] ?? 7;

        $field = ['length' => $length, 'fixed' => true];
        if (!$platform instanceof MySqlPlatform) {
            return $platform->getVarcharTypeDeclarationSQL($field);
        }
        $field['collation'] = 'utf8_bin';

        return $platform->getVarcharTypeDeclarationSQL($field).' '.$platform->getColumnCollationDeclarationSQL('utf8_bin');
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return;
        }
        if (ShortId::isValid($value)) {
            return new Shortid($value);
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return;
        }
        if ($value instanceof ShortId || ShortId::isValid($value)) {
            return $value;
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
