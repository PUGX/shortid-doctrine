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
            if (\is_callable([$platform, 'getVarcharTypeDeclarationSQL'])) {
                return $platform->getVarcharTypeDeclarationSQL($field);
            }

            return $platform->getStringTypeDeclarationSQL($field);
        }
        $field['collation'] = 'utf8_bin';
        $collation = $platform->getColumnCollationDeclarationSQL('utf8_bin');

        if (\is_callable([$platform, 'getVarcharTypeDeclarationSQL'])) {
            return $platform->getVarcharTypeDeclarationSQL($field).' '.$collation;
        }

        return $platform->getStringTypeDeclarationSQL($field).' '.$collation;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Shortid
    {
        if (empty($value)) {
            return null;
        }
        if (Shortid::isValid($value)) {
            return new Shortid($value);
        }

        if (\is_callable([ConversionException::class, 'conversionFailed'])) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        throw new ConversionException(\sprintf('Invalid value %s for type %s', $value, self::NAME));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }
        if ($value instanceof Shortid || Shortid::isValid($value)) {
            return (string) $value;
        }

        if (\is_callable([ConversionException::class, 'conversionFailed'])) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        throw new ConversionException(\sprintf('Invalid value %s for type %s', $value, self::NAME));
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
