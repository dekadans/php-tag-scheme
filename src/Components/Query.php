<?php

namespace tthe\TagScheme\Components;

use tthe\TagScheme\Contracts\UriPart;

readonly class Query implements UriPart
{
    public function __construct(
        private array $parameters
    ) {}

    public function encoded(): string
    {
        return http_build_query(
            $this->parameters,
            encoding_type: PHP_QUERY_RFC3986
        );
    }

    public function value(): array
    {
        return $this->parameters;
    }
}