<?php

namespace tthe\TagScheme\Components;

use tthe\TagScheme\Contracts\UriPart;
use tthe\TagScheme\Exceptions\TagSchemeException;

readonly class Date implements UriPart
{
    private \DateTimeImmutable $datetime;

    /** @throws TagSchemeException */
    public function __construct(\DateTimeImmutable $datetime)
    {
        if ($datetime->getOffset() !== 0) {
            throw new TagSchemeException('Date must be provided in UTC.');
        }
        if ($datetime->format('H:i:s') !== '00:00:00') {
            throw new TagSchemeException('Time must be set to midnight.');
        }

        $this->datetime = $datetime;
    }

    public function encoded(): string
    {
        $month = (int) $this->datetime->format("n");
        $day = (int) $this->datetime->format("j");

        $format = match (true) {
            $month === 1 && $day === 1 => 'Y',
            $day === 1 => 'Y-m',
            default => 'Y-m-d'
        };

        return $this->datetime->format($format);
    }

    public function value(): \DateTimeImmutable
    {
        return $this->datetime;
    }
}