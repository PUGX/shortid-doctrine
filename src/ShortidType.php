<?php

declare(strict_types=1);

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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Shortid
    {
        if (empty($value)) {
            return null;
        }
        if (ShortId::isValid($value)) {
            return new Shortid($value);
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }
        if ($value instanceof ShortId || ShortId::isValid($value)) {
            return (string) $value;
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
