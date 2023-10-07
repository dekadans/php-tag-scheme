<?php

namespace tthe\TagScheme\Contracts;

/**
 * A Tagging Entity is an object capable of minting Tag URIs.
 */
interface TaggingEntityInterface
{
    /**
     * Creates a (stringable) tag URI for a given resource.
     */
    public function mint(string $resource): TagInterface;

    public function getAuthority(): AuthorityInterface;

    /** @return UriPart<\DateTimeImmutable> */
    public function getDate(): UriPart;
}