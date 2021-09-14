<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Mother\Domain\DateTime\ValueObject;

use DateTimeZone;
use PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime;
use PB\Component\CQRS\Tests\Helper\FakerTrait;
use PB\Component\CQRS\Tests\Helper\Mother\SimpleValueObjectMother;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DateTimeMother extends SimpleValueObjectMother
{
    use FakerTrait;

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return DateTime::class;
    }

    /**
     * @return array
     */
    protected static function getDefaultConstructorArguments(): array
    {
        $dateTime = self::getFaker()->dateTime();
        $timezone = new DateTimeZone('Europe/Warsaw');

        return [
            'datetime' => $dateTime->format('Y-m-d H:i:s'),
            'timezone' => $timezone,
        ];
    }
}
