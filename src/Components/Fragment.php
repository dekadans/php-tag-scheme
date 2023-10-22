<?php
declare(strict_types=1);

namespace tthe\TagScheme\Components;

use tthe\TagScheme\Contracts\UriPart;

readonly class Fragment implements UriPart
{
    use EncodeUriPart;

    public function __construct(
        private string $raw
    ) {}

    public function encoded(): string
    {
        return $this->encode(
            $this->raw,
            Charsets::ALLOWED_FRAGMENT()
        );
    }

    public function value(): string
    {
        return $this->raw;
    }
}