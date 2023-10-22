<?php

namespace tthe\TagScheme\Contracts;

use tthe\TagScheme\Components\AuthorityType;

/** @extends UriPart<string> */
interface AuthorityInterface extends UriPart
{
    public function type(): AuthorityType;
}