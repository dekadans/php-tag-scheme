<?php
declare(strict_types=1);

use tthe\TagScheme\Components\StringTag;
use tthe\TagScheme\Contracts\TagInterface;
use tthe\TagScheme\Exceptions\TagSchemeException;
use tthe\TagScheme\Tag;

describe('StringTag', function () {
    test('parse simple uri', function () {
        $uri = 'tag:example.org,2023:test';
        $parsed = (new StringTag($uri))->parse();

        expect($parsed)->toBeInstanceOf(TagInterface::class);
    });

    test('works through static method', function () {
        $uri = 'tag:example.org,2023:test';
        $parsed = Tag::fromString($uri);

        expect($parsed)->toBeInstanceOf(TagInterface::class);
    });

    test('has basic properties', function () {
        $uri = 'tag:example.org,2023:test';
        $parsed = (new StringTag($uri))->parse();

        expect($parsed->getAuthority()->value())->toBe('example.org');
        expect($parsed->getDate()->value())->toBeInstanceOf(DateTimeImmutable::class);
        expect($parsed->getDate()->value()->format('Y-m-d'))->toBe('2023-01-01');
        expect($parsed->getResource()->value())->toBe('test');
    });

    test('handles percent encoding', function () {
        $uri = 'tag:example.org,2023:test%20%3F%23%C3%A9';
        $parsed = (new StringTag($uri))->parse();

        expect($parsed->getResource()->value())->toBe('test ?#é');
    });

    test('handles fragment and query', function () {
        $uri = 'tag:example.org,2023:test?k=v%20&o=m#frag%20';
        $parsed = (new StringTag($uri))->parse();

        expect($parsed->getQuery()->value())->toBe(['k' => 'v ', 'o' => 'm']);
        expect($parsed->getFragment()->value())->toBe('frag ');
    });

    test('accepts empty resource', function () {
        $uri = 'tag:example.org,2023:';
        $parsed = (new StringTag($uri))->parse();

        expect($parsed->getResource()->value())->toBe('');
    });

    test('accepts full date', function () {
        $uri = 'tag:example.org,2023-04-12:test';
        $parsed = (new StringTag($uri))->parse();

        expect($parsed->getDate()->encoded())->toBe('2023-04-12');
    });

    test('throws exception for non-tags', function () {
        $uri = 'https://example.org/test';
        $parsed = (new StringTag($uri))->parse();
    })->throws(TagSchemeException::class);

    test('throws exception for malformed tag', function () {
        $uri = 'tag:example.org,what:test';
        $parsed = (new StringTag($uri))->parse();
    })->throws(TagSchemeException::class);
});