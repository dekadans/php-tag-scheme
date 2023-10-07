<?php

use tthe\TagScheme\Components\Date;
use tthe\TagScheme\Components\Fragment;
use tthe\TagScheme\Components\Query;
use tthe\TagScheme\Components\Resource;
use tthe\TagScheme\Exceptions\TagSchemeException;

describe('Date', function() {
    test('value returns original object', function() {
        $dt = new DateTimeImmutable('midnight', new DateTimeZone('UTC'));
        $date = new Date($dt);

        expect($date->value())->toBe($dt);
    });

    test('encoded returns full date', function() {
        $dt = new DateTimeImmutable('2023-05-02', new DateTimeZone('UTC'));
        $date = new Date($dt);

        expect($date->encoded())->toBe('2023-05-02');
    });

    test('encoded strips day', function() {
        $dt = new DateTimeImmutable('2023-05-01', new DateTimeZone('UTC'));
        $date = new Date($dt);

        expect($date->encoded())->toBe('2023-05');
    });

    test('encoded strips day and month', function() {
        $dt = new DateTimeImmutable('2023-01-01', new DateTimeZone('UTC'));
        $date = new Date($dt);

        expect($date->encoded())->toBe('2023');
    });

    test('exception for non-UTC datetimes', function() {
        $dt = new DateTimeImmutable('midnight', new DateTimeZone('CET'));
        $date = new Date($dt);
    })->throws(TagSchemeException::class);

    test('exception for non-midnight datetimes', function() {
        $dt = new DateTimeImmutable('12:23:45', new DateTimeZone('UTC'));
        $date = new Date($dt);
    })->throws(TagSchemeException::class);
});

describe('Fragment', function () {
    test('value returns original string', function() {
        $f = new Fragment('frág');
        expect($f->value())->toBe('frág');
    });

    test('encoded returns encoded string', function() {
        $f = new Fragment('~frág');
        expect($f->encoded())->toBe('~fr%C3%A1g');
    });
});

describe('Resource', function () {
    test('value returns original string', function() {
        $r = new Resource('resourcé');
        expect($r->value())->toBe('resourcé');
    });

    test('encoded returns encoded string', function() {
        $r = new Resource('~resourcé');
        expect($r->encoded())->toBe('~resourc%C3%A9');
    });
});

describe('Query', function () {
    test('value returns original array', function() {
        $a = ['some-key' => 'some-value'];
        $q = new Query($a);
        expect($q->value())->toBe($a);
    });

    test('encoded returns encoded string', function() {
        $a = ['some-key' => 'some-valué'];
        $q = new Query($a);
        expect($q->encoded())->toBe('some-key=some-valu%C3%A9');
    });
});