<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Helper;

use Faker\{Factory, Generator};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait FakerTrait
{
    /**
     * @var Generator[]
     */
    private static array $_fakers = [];

    /**
     * @param string $locale
     *
     * @return Generator
     */
    protected static function getFaker(string $locale = Factory::DEFAULT_LOCALE): Generator
    {
        return self::$_fakers[$locale] ?? (self::$_fakers[$locale] = Factory::create($locale));
    }
}
