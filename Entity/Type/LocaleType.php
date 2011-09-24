<?php

namespace BeSimple\RosettaBundle\Entity\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class LocaleType extends Type
{
    static public $useRegion = true;

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function getDefaultLength(AbstractPlatform $platform)
    {
        return 7;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Locale(static::$useRegion, (string) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    public function getName()
    {
        return 'locale';
    }
}
