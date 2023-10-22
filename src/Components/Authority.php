<?php
declare(strict_types=1);

namespace tthe\TagScheme\Components;

use tthe\TagScheme\Contracts\AuthorityInterface;
use tthe\TagScheme\Exceptions\TagSchemeException;

readonly class Authority implements AuthorityInterface {
    private string $value;
    private AuthorityType $type;

    /** @throws TagSchemeException */
    public function __construct(AuthorityType $type, string $value)
    {
        if (!$type->validate($value)) {
            throw new TagSchemeException("Invalid authority name '$value'");
        }

        $this->type = $type;
        $this->value = strtolower($value);
    }

    public function type(): AuthorityType
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function encoded(): string
    {
        return $this->value;
    }

    /** @throws TagSchemeException */
    public static function from(string $authority): static
    {
        foreach (AuthorityType::cases() as $type) {
            if ($type->validate($authority)) {
                return new static($type, $authority);
            }
        }

        throw new TagSchemeException("Could not parse '$authority' into a valid tagging authority.");
    }
}