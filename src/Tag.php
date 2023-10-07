<?php
declare(strict_types=1);

namespace tthe\TagScheme;

use tthe\TagScheme\Components\Authority;
use tthe\TagScheme\Components\Date;
use tthe\TagScheme\Components\Fragment;
use tthe\TagScheme\Components\Query;
use tthe\TagScheme\Components\Resource;
use tthe\TagScheme\Components\StringTag;
use tthe\TagScheme\Contracts\TagInterface;
use tthe\TagScheme\Exceptions\TagSchemeException;

class Tag implements TagInterface
{
    private Authority $authority;
    private Date $date;
    private Resource $resource;
    private ?Query $query = null;
    private ?Fragment $fragment = null;

    public function __construct(
        Authority $authority,
        Date $date,
        Resource $resource
    ) {
        $this->authority = $authority;
        $this->date = $date;
        $this->resource = $resource;

    }

    /** @throws TagSchemeException */
    public static function fromString(string $uri): TagInterface
    {
        return (new StringTag($uri))->parse();
    }

    public function withQuery(array $parameters): TagInterface
    {
        $tag = clone $this;
        $tag->query = new Query($parameters);
        return $tag;
    }

    public function withFragment(string $fragment): TagInterface
    {
        $tag = clone $this;
        $tag->fragment = new Fragment($fragment);
        return $tag;
    }

    public function toString(): string
    {
        return (string) StringTag::build($this);
    }

    public function getAuthority(): Authority
    {
        return $this->authority;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function getResource(): Resource
    {
        return $this->resource;
    }

    public function getQuery(): ?Query
    {
        return $this->query;
    }

    public function getFragment(): ?Fragment
    {
        return $this->fragment;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}