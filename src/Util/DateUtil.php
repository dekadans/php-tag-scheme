<?php

namespace tthe\TagScheme\Util;

use tthe\TagScheme\Exceptions\TagSchemeException;

enum DateUtil
{
    case TODAY;
    case FIRST_OF_MONTH;
    case FIRST_OF_YEAR;

    /**
     * Utility for creating timezone-aware datetimes from a string.
     *
     * @throws TagSchemeException
     */
    public static function date(string $date): \DateTimeImmutable
    {
        try {
            $dt = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
            return $dt->modify('midnight');
        } catch (\Exception $e) {
            throw new TagSchemeException("Could not process date '$date'");
        }
    }

    /**
     * Utility for creating potentially shortened dates in a URI.
     * I.e. '2023' => DateTimeImmutable('2023-01-01')
     *
     * @throws TagSchemeException
     */
    public static function fromUriDate(string $date): \DateTimeImmutable
    {
        $date = match (strlen($date)) {
            4 => $date . '-01-01',
            7 => $date . '-01',
            10 => $date,
            default => throw new TagSchemeException("Date '$date' is not a valid format for Tag URIs.")
        };

        return self::date($date);
    }

    public function get(): \DateTimeImmutable
    {
        $tz = new \DateTimeZone('UTC');

        $dateStr = match ($this) {
            DateUtil::TODAY => 'midnight',
            DateUtil::FIRST_OF_MONTH => 'midnight first day of this month',
            DateUtil::FIRST_OF_YEAR => 'January 1st'
        };

        return new \DateTimeImmutable($dateStr, $tz);
    }
}
