<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\MotherObject\Domain\DateTime\ValueObject;

use DateTimeZone;
use PB\Component\CQRS\Domain\DateTime\ValueObject\DateTime;
use PB\Component\FirstAidTests\Faker\FakerTrait;
use PB\Component\FirstAidTests\MotherObject\SimpleValueObjectMother;

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
        $timezone = new DateTimeZone('UTC');

        return [
            'datetime' => $dateTime->format('Y-m-d H:i:s'),
            'timezone' => $timezone,
        ];
    }
}
