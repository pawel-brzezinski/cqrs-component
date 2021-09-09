<?php

declare(strict_types=1);

namespace PB\Component\CQRS\Tests\Helper\Fake;

use PB\Component\CQRS\Helper\ValueAccessorTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeClass
{
    use ValueAccessorTrait;

    private static string $lid = 'Lorem Ipsum Dolor';

    private string $lorem = 'lorem';

    private string $ipsum = 'ipsum';

    private string $dolor = 'dolor';

    private string $sit = 'sit';

    /**
     * @param string $part1
     * @param string $part2
     *
     * @return string
     */
    private static function mixDolor(string $part1, string $part2): string
    {
        $object = new self();
        $object->setDolor($part1.'|'.$part2);

        return $object->dolor();
    }

    /**
     * @return string
     */
    public function lorem(): string
    {
        return $this->lorem;
    }

    /**
     * @param string $lorem
     *
     * @return string
     */
    public function setLorem(string $lorem): string
    {
        $this->lorem = $lorem;

        return $this->lorem();
    }

    /**
     * @return string
     */
    protected function ipsum(): string
    {
        return $this->ipsum;
    }

    /**
     * @param string $ipsum
     *
     * @return string
     */
    protected function setIpsum(string $ipsum): string
    {
        $this->ipsum = $ipsum;

        return $this->ipsum();
    }


    /**
     * @return string
     */
    private function dolor(): string
    {
        return $this->dolor;
    }

    /**
     * @param string $dolor
     */
    private function setDolor(string $dolor): void
    {
        $this->dolor = $dolor;
    }
}
