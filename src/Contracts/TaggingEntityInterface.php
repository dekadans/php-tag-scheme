<?php

namespace tthe\TagScheme\Contracts;

interface TaggingEntityInterface
{
    public function mint(string $resource): TagInterface;

    public function getAuthority(): AuthorityInterface;

    /** @return UriPart<\DateTimeImmutable> */
    public function getDate(): UriPart;
}