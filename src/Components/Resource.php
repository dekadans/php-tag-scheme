<?php
declare(strict_types=1);

namespace tthe\TagScheme\Components;

use tthe\TagScheme\Contracts\UriPart;

readonly class Resource implements UriPart
{
    use EncodeUriPart;

    public function __construct(
        private string $raw
    ) {}

    public function encoded(): string
    {
        return $this->encode(
            $this->raw,
            Charsets::ALLOWED_RESOURCE()
        );
    }

    public function value(): string
    {
        return $this->raw;
    }
}