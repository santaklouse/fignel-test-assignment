<?php

namespace FignelTestAssignment\Helper;

class Utils
{
    public static function color2Rgba(array $color): string
    {
        if (empty($color))
            return '';

        [ 'r' => $r, 'g' => $g, 'b' => $b, 'a' => $a ] = $color;

        $value =  array_map(fn($v) => round(255 * $v), [$r, $g, $b]);
        $value = join(', ', $value);
        return "rgba({$value}, {$a})";
    }

    public static function escapeSpecialChars(string $str, $replaceWith = '_'): string
    {
        if (empty($str))
            return $str;

        $charsList = [ '\'', '"', ',' , ';', '<', '>', ':', '+', '^', '%', '!', '$', '@', '&', '*' ];

        return str_ireplace($charsList, $replaceWith, $str);
    }

}
