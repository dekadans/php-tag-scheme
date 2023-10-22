<?php

namespace tthe\TagScheme\Contracts;

use Psr\Http\Message\UriInterface;

/**
 * A URI with the "tag" scheme, as defined in RFC 4151.
 */
interface TagInterface extends \Stringable, \JsonSerializable
{
    public function toString(): string;
    public function toPsr7(): UriInterface;

    public function withQuery(array $parameters): TagInterface;
    public function withFragment(string $fragment): TagInterface;

    public function getAuthority(): AuthorityInterface;

    /** @return UriPart<\DateTimeImmutable> */
    public function getDate(): UriPart;

    /** @return UriPart<string> */
    public function getResource(): UriPart;

    /** @return ?UriPart<string[]> */
    public function getQuery(): ?UriPart;

    /** @return ?UriPart<string> */
    public function getFragment(): ?UriPart;
}