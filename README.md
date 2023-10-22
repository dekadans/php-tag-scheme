# TagScheme

A PHP implementation of RFC 4151: The 'tag' URI Scheme.

A Tag URI is an identifier for a specific resource tied to a domain name or e-mail address at a given point in time.
It can look something like `tag:example.org,2023:resource`.

It can be used when something should be identified using a human-readable URI, instead of non-resolvable HTTP URIs.

More information at [taguri.org](https://www.taguri.org/) and [the RFC](https://www.rfc-editor.org/rfc/rfc4151).

## Installation

[Composer](https://getcomposer.org/):

```
composer require tthe/php-tag-scheme
```

## Basic Usage

```php
$te = new \tthe\TagScheme\TaggingEntity('example.org');
$tag = $te->mint('something');

echo $tag; // Prints "tag:example.org,2023-10-22:something"
```

## Tagging Entity

Tag URIs are created through a `TaggingEntity` object based on a domain name or an e-mail address and a date.
The date defaults to today but can be set in various ways using `DateUtil`.

```php
use tthe\TagScheme\TaggingEntity;
use tthe\TagScheme\Util\DateUtil;

// The tagging authority can be either domain name or an e-mail address.
$authority = 'example.org';

// If no date is provided it will default to today.
$te1 = new TaggingEntity($authority);

// We can also set it to January 1 of the current year...
$te2 = new TaggingEntity($authority, DateUtil::FIRST_OF_YEAR);

// ...or the first day of the current month...
$te3 = new TaggingEntity($authority, DateUtil::FIRST_OF_MONTH);

// ...or an explicit date.
$te4 = new TaggingEntity($authority, DateUtil::date('2020-04-17'));
```

## Tag Objects

Minted tag URIs are objects implementing `TagInterface`, and extends `Stringable` and `JsonSerializable`.

```php
use tthe\TagScheme\TaggingEntity;
use tthe\TagScheme\Util\DateUtil;

$te = new TaggingEntity('demo@example.org', DateUtil::FIRST_OF_YEAR);
$tag = $te->mint('something');

echo $tag->toString();
// tag:demo@example.org,2023:something

echo $tag->getAuthority()->value();
// demo@example.org

echo $tag->getDate()->value()->format('Y-m-d');
// 2023-01-01

echo $tag->getResource()->value();
// something
```

## Parsing Strings

It's also possible to do it the other way around:

```php
$s = 'tag:example.org,2023:something';
$tag = \tthe\TagScheme\Tag::fromString($s);

echo $tag->getResource()->value();
// something
```

## Query Parameters and Fragments

Tags, like all URIs, support query and fragment components.
However, preserving human readability should always be prioritized.

```php
use tthe\TagScheme\TaggingEntity;
use tthe\TagScheme\Util\DateUtil;

$te = new TaggingEntity('demo@example.org', DateUtil::FIRST_OF_YEAR);
echo $te->mint('something')
    ->withQuery(['param' => 'value'])
    ->withFragment('subresource');

// tag:demo@example.org,2023:something?param=value#subresource
```

## PSR-7

For convenience, and despite the fact that tag URIs are not resolvable,
a conversion method to the PSR-7 UriInterface is provided.

```php
$s = 'tag:example.org,2023:something';
$tag = \tthe\TagScheme\Tag::fromString($s);

$psrImpl = $tag->toPsr7();
```

## Classes and Interfaces

`\tthe\TagScheme\TaggingEntity` implements `\tthe\TagScheme\Contracts\TaggingEntityInterface`

`\tthe\TagScheme\Tag` implements `\tthe\TagScheme\Contracts\TagInterface`

A possible pattern can be to initiate a TaggingEntity object centrally in your project and map it to
TaggingEntityInterface in your dependency injection container.