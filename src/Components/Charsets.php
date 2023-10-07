<?php

namespace tthe\TagScheme\Components;

class Charsets
{
    /*
     * unreserved, sub-delims, pchar as defined by RFC 3986
     * https://www.rfc-editor.org/rfc/rfc3986
     */

    private static function UNRESERVED(): array
    {
        return array_merge(
            range('A', 'Z'),
            range('a', 'z'),
            array_map(
                strval(...),
                range(0,9)
            ),
            ['-', '_', '.', '~']
        );
    }

    private static function SUB_DELIMS(): array
    {
        return ["!", "$", "&", "'", "(", ")", "*", "+", ",", ";", "="];
    }

    private static function PCHAR(): array
    {
        return array_merge(
            self::UNRESERVED(),
            self::SUB_DELIMS(),
            [':', '@']
        );
    }

    public const QUERY_DELIM = '?';

    public static function ALLOWED_RESOURCE(): array
    {
        return array_merge(
            self::PCHAR(),
            ['/']
        );
    }

    public static function ALLOWED_FRAGMENT(): array
    {
        return array_merge(
            self::ALLOWED_RESOURCE(),
            [self::QUERY_DELIM]
        );
    }
}