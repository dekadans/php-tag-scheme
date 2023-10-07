<?php

namespace tthe\TagScheme\Contracts;

use tthe\TagScheme\Components\AuthorityType;

interface AuthorityInterface
{
    public function type(): AuthorityType;

    public function value(): string;
}