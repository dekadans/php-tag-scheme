<?php

namespace tthe\TagScheme;

use tthe\TagScheme\Components\Authority;
use tthe\TagScheme\Components\Date;
use tthe\TagScheme\Components\Resource;
use tthe\TagScheme\Contracts\TaggingEntityInterface;
use tthe\TagScheme\Contracts\TagInterface;
use tthe\TagScheme\Exceptions\TagSchemeException;
use tthe\TagScheme\Util\DateUtil;

class TaggingEntity implements TaggingEntityInterface
{
    private Authority $authority;

    private Date $date;

    /**
     * @param Authority|string $authority
     * @param DateUtil|\DateTimeImmutable $date
     * @throws TagSchemeException
     */
    public function __construct(
        Authority|string $authority,
        DateUtil|\DateTimeImmutable $date = DateUtil::TODAY
    ) {
        if (is_string($authority)) {
            $authority = Authority::from($authority);
        }

        if ($date instanceof DateUtil) {
            $date = $date->get();
        }

        $this->authority = $authority;
        $this->date = new Date($date);
    }


    public function mint(string $resource): TagInterface
    {
        return new Tag(
            $this->authority,
            $this->date,
            new Resource($resource)
        );
    }

    public function getAuthority(): Authority
    {
        return $this->authority;
    }

    public function getDate(): Date
    {
        return $this->date;
    }
}