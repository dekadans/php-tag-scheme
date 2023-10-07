<?php

namespace tthe\TagScheme\Components;

enum AuthorityType
{
    case EMAIL_ADDRESS;
    case DNS_NAME;

    public function validate(string $value): bool
    {
        return match ($this) {
            AuthorityType::DNS_NAME => filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME),
            AuthorityType::EMAIL_ADDRESS => filter_var($value, FILTER_VALIDATE_EMAIL),
        };
    }
}
