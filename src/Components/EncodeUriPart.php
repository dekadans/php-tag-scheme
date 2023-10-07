<?php

namespace tthe\TagScheme\Components;

trait EncodeUriPart
{
    private function encode(string $value, array $charset): string
    {
        $chars = str_split($value);

        return implode(
            array_map(
                fn($char) => in_array($char, $charset) ? $char : rawurlencode($char),
                $chars
            )
        );
    }
}