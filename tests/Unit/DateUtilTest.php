<?php
declare(strict_types=1);

use tthe\TagScheme\Exceptions\TagSchemeException;
use tthe\TagScheme\Util\DateUtil;

describe('DateUtil', function () {
    $today = new DateTimeImmutable('now', new DateTimeZone('UTC'));

    test('today should be correct', function () use ($today) {
        $expected = $today->format('Y-m-d');
        $actual = DateUtil::TODAY->get();

        expect($actual)->toBeInstanceOf(DateTimeImmutable::class);
        expect($actual->format('Y-m-d'))->toBe($expected);
        expect($actual->format('H:i:s'))->toBe('00:00:00');
        expect($actual->getOffset())->toBe(0);
    });

    test('first of month should be correct', function () use ($today) {
        $expected = $today->format('Y-m');
        $actual = DateUtil::FIRST_OF_MONTH->get();

        expect($actual)->toBeInstanceOf(DateTimeImmutable::class);
        expect($actual->format('Y-m-d'))->toBe($expected . '-01');
        expect($actual->format('H:i:s'))->toBe('00:00:00');
        expect($actual->getOffset())->toBe(0);
    });

    test('first of year should be correct', function () use ($today) {
        $expected = $today->format('Y');
        $actual = DateUtil::FIRST_OF_YEAR->get();

        expect($actual)->toBeInstanceOf(DateTimeImmutable::class);
        expect($actual->format('Y-m-d'))->toBe($expected . '-01-01');
        expect($actual->format('H:i:s'))->toBe('00:00:00');
        expect($actual->getOffset())->toBe(0);
    });

    test('fromUriDate parses only year', function () {
        $date = DateUtil::fromUriDate('2020');

        expect($date->format('Y-m-d'))->toBe('2020-01-01');
        expect($date->format('H:i:s'))->toBe('00:00:00');
        expect($date->getOffset())->toBe(0);
    });

    test('fromUriDate parses only year and month', function () {
        $date = DateUtil::fromUriDate('2020-03');

        expect($date->format('Y-m-d'))->toBe('2020-03-01');
        expect($date->format('H:i:s'))->toBe('00:00:00');
        expect($date->getOffset())->toBe(0);
    });

    test('fromUriDate parses full date', function () {
        $date = DateUtil::fromUriDate('2020-03-17');

        expect($date->format('Y-m-d'))->toBe('2020-03-17');
        expect($date->format('H:i:s'))->toBe('00:00:00');
        expect($date->getOffset())->toBe(0);
    });

    test('fromUriDate fails for other date', function () {
        $date = DateUtil::fromUriDate('2020-03-17 04:13:45');
    })->throws(TagSchemeException::class);

    test('date() creates date', function () {
        $date = DateUtil::date('2020-03-17');

        expect($date->format('Y-m-d'))->toBe('2020-03-17');
        expect($date->format('H:i:s'))->toBe('00:00:00');
        expect($date->getOffset())->toBe(0);
    });

    test('date() uses resets time', function () {
        $date = DateUtil::date('2020-03-17 02:16:17');

        expect($date->format('Y-m-d'))->toBe('2020-03-17');
        expect($date->format('H:i:s'))->toBe('00:00:00');
        expect($date->getOffset())->toBe(0);
    });

    test('date() fails for invalid data', function () {
        $date = DateUtil::date('!!!');
    })->throws(TagSchemeException::class);
});