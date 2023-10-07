<?php

namespace tthe\TagScheme\Contracts;

/** @template T */
interface UriPart
{
    /**
     * Returns the string value of a URI component, percent-encoded if needed.
     *
     * @return string
     */
    public function encoded(): string;

    /**
     * Returns the raw representation. The type will depend on the component:
     * date = instance of DateTimeImmutable
     * resource = string, non-encoded
     * query = associative array
     * fragment = string, non-encoded
     *
     * @return T
     */
    public function value(): mixed;
}