# TagScheme

A PHP implementation of RFC 4151: The 'tag' URI Scheme.

A Tag URI is an identifier for a specific resource tied to a domain name or e-mail address at a given point in time.
It can look something like `tag:example.org,2023:some-resource`.

It can be used when something should be identified using a human-readable URI, instead of non-resolvable HTTP URIs.

More information at [taguri.org](https://www.taguri.org/) and [the RFC](https://www.rfc-editor.org/rfc/rfc4151).

## Installation

[Composer](https://getcomposer.org/):

```
composer require tthe/php-tag-scheme
```

## Basic Usage

Tag URIs are created through a `TaggingEntity` object based on a domain name or an e-mail address and a date (defaults to today).

```php
$te = new \tthe\TagScheme\TaggingEntity('example.org');
echo $te->mint('some-resource');

// tag:example.org,2023-10-07:some-resource
// (date is whatever today's date is)
```

It's also possible to set the date to the beginning of the current year:

```php
use tthe\TagScheme\TaggingEntity;
use tthe\TagScheme\Util\DateUtil;

$te = new TaggingEntity('example.org', DateUtil::FIRST_OF_YEAR);
echo $te->mint('some-resource');

// tag:example.org,2023:some-resource
```

The minted tag URI is actually a `Tag` object that implements `Stringable` and `JsonSerializable`.

## Parsing Strings

It's also possible to do it the other way around:

```php
$s = 'tag:example.org,2023:some-resource';
$tag = \tthe\TagScheme\Tag::fromString($s);

echo $tag->getResource()->value();

// some-resource
```

## Query Parameters and Fragments

Tags, like all URIs, support query and fragment components.
However, preserving human readability should always be prioritized.

```php
use tthe\TagScheme\TaggingEntity;
use tthe\TagScheme\Util\DateUtil;

$te = new TaggingEntity('example.org', DateUtil::FIRST_OF_YEAR);
echo $te->mint('some-resource')
    ->withQuery(['param' => 'value'])
    ->withFragment('subresource');

// tag:example.org,2023:some-resource?param=value#subresource
```

## Classes and Interfaces

`\tthe\TagScheme\TaggingEntity` implements `\tthe\TagScheme\Contracts\TaggingEntityInterface`

`\tthe\TagScheme\Tag` implements `\tthe\TagScheme\Contracts\TagInterface`

A possible pattern is for example to initiate a TaggingEntity object centrally in your project and map it to
TaggingEntityInterface in your dependency injection container.