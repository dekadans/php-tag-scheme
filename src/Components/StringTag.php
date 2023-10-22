<?php
declare(strict_types=1);

namespace tthe\TagScheme\Components;

use tthe\TagScheme\Contracts\TagInterface;
use tthe\TagScheme\Exceptions\TagSchemeException;
use tthe\TagScheme\Tag;
use tthe\TagScheme\Util\DateUtil;

/**
 * Wrapper class for a string Tag URI
 * with methods for converting to and from Tag objects.
 */
readonly class StringTag implements \Stringable
{
    private const SCHEME = 'tag';

    private const REGEX = '/^(.+),([0-9]{4}(?:-[0-9]{2}){0,2}):(.*)$/';

    public function __construct(
        private string $uri
    ) {}

    public static function build(TagInterface $tag): static
    {
        $uri = sprintf(
            "%s:%s,%s:%s",
            self::SCHEME,
            $tag->getAuthority()->value(),
            $tag->getDate()->encoded(),
            $tag->getResource()->encoded()
        );

        if ($tag->getQuery()) {
            $uri .= '?' . $tag->getQuery()->encoded();
        }

        if ($tag->getFragment()) {
            $uri .= '#' . $tag->getFragment()->encoded();
        }

        return new static($uri);
    }
    
    /**
     * @throws TagSchemeException
     */
    public function parse(): TagInterface
    {
        $parts = parse_url($this->uri);

        if (!is_array($parts)) {
            throw new TagSchemeException('Malformed URI.');
        }

        $scheme = $parts['scheme'] ?? '';
        $path = $parts['path'] ?? '';
        $query = $parts['query'] ?? null;
        $fragment = $parts['fragment'] ?? null;

        if (strtolower($scheme) !== self::SCHEME) {
            throw new TagSchemeException("Invalid scheme for Tag URI: {$parts['scheme']}");
        }

        $matches = [];

        if (preg_match(self::REGEX, $path, $matches) !== 1) {
            throw new TagSchemeException("Could not parse tagging entity and resource from '$path'.");
        }

        [, $authority, $date, $resource] = $matches;

        $tag = new Tag(
            Authority::from($authority),
            new Date(DateUtil::fromUriDate($date)),
            new Resource(rawurldecode($resource))
        );

        if ($query !== null) {
            $queryParameters = [];
            parse_str($query, $queryParameters);
            $tag = $tag->withQuery($queryParameters);
        }

        if ($fragment !== null) {
            $tag = $tag->withFragment(rawurldecode($fragment));
        }

        return $tag;
    }

    public function __toString(): string
    {
        return $this->uri;
    }
}