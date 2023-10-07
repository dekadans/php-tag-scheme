<?php

use tthe\TagScheme\Components\Authority;
use tthe\TagScheme\Components\AuthorityType;
use tthe\TagScheme\Exceptions\TagSchemeException;
use tthe\TagScheme\TaggingEntity;
use tthe\TagScheme\Util\DateUtil;

describe('TaggingEntity - Authority', function () {
    test('should identify email', function () {
        $email = 'mail@example.org';
        $m = new TaggingEntity($email);
        $a = $m->getAuthority();

        expect($a->type())->toBe(AuthorityType::EMAIL_ADDRESS);
        expect($a->value())->toBe($email);
    });

    test('should accept explicit email', function () {
        $email = 'mail@example.org';
        $m = new TaggingEntity(new Authority(AuthorityType::EMAIL_ADDRESS, $email));

        expect($m->getAuthority()->value())->toBe($email);
    });

    test('should identify domain name', function () {
        $domain = 'example.org';
        $m = new TaggingEntity($domain);
        $a = $m->getAuthority();

        expect($a->type())->toBe(AuthorityType::DNS_NAME);
        expect($a->value())->toBe($domain);
    });

    test('should work for localhost', function () {
        $domain = 'localhost';
        $m = new TaggingEntity($domain);
        $a = $m->getAuthority();

        expect($a->type())->toBe(AuthorityType::DNS_NAME);
        expect($a->value())->toBe($domain);
    });

    test('should accept explicit domain', function () {
        $domain = 'example.org';
        $m = new TaggingEntity(new Authority(AuthorityType::DNS_NAME, $domain));

        expect($m->getAuthority()->value())->toBe($domain);
    });

    test('should throw exception', function () {
        $m = new TaggingEntity('!!!');
    })->throws(TagSchemeException::class);

});

describe('TaggingEntity - Date', function () {
    test('should default to today', function () {
        $today = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d');
        $m = new TaggingEntity('example.org');
        $date = $m->getDate()->value()->format('Y-m-d');

        expect($date)->toBe($today);
    });

    test('should accept util', function () {
        $date = DateUtil::FIRST_OF_YEAR->get()->format('Y-m-d');
        $m = new TaggingEntity('example.org', DateUtil::FIRST_OF_YEAR);

        $parsedDate = $m->getDate()->value()->format('Y-m-d');

        expect($parsedDate)->toBe($date);
    });

    test('create from string date', function () {
        $dateStr = '2023-04-15';
        $date = DateUtil::date($dateStr);
        $m = new TaggingEntity('example.org', $date);

        $parsedDate = $m->getDate()->value()->format('Y-m-d');

        expect($parsedDate)->toBe($dateStr);
    });

    test('should accept datetime', function () {
        $today = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $m = new TaggingEntity('example.org', $today);

        expect($m->getDate()->value())->toBe($today);
    });
});


describe('TaggingEntity - Mint', function () {
    test('should produce tag', function() {
        $m = new TaggingEntity('example.org');
        $t = $m->mint('test');
        expect($t)->toBeInstanceOf(\tthe\TagScheme\Contracts\TagInterface::class);
        expect($t)->toBeInstanceOf(\tthe\TagScheme\Tag::class);
    });

    test('should have correct resource', function() {
        $m = new TaggingEntity('example.org');
        $t = $m->mint('test');

        expect($t->getResource()->value())->toBe('test');
    });
});