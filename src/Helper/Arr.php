<?php

namespace FignelTestAssignment\Helper;

use ArrayAccess;
use Traversable;

class Arr
{
    private static string $delimiter = '.';

    protected static function hasDelimiter($str): bool
    {
        return strpos($str, self::$delimiter) > -1;
    }

    public static function accessible($value): bool
    {
        if (is_array($value)) {
            // Definitely an array
            return TRUE;
        } else {
            // Possibly a Traversable object, functionally the same as an array
            return (is_object($value) AND ($value instanceof Traversable || $value instanceof ArrayAccess));
        }
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  array $array
     * @param  string|array $keys
     * @return bool
     */
    public static function has($array, $key)
    {
        if (!self::accessible($array)) {
            return FALSE;
        }

        if (empty($array) || empty($key)) {
            return FALSE;
        }

        if (self::keyExists($key, $array)) {
            return TRUE;
        }

        if (!self::hasDelimiter($key)) {
            return FALSE;
        }

        foreach (explode(self::$delimiter, $key) as $segment) {
            if (self::accessible($array) && self::keyExists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array|object $array
     * @param  string|int|null $key
     * @param  mixed $default
     * @return mixed
     */
    public static function keyExists($key, $array)
    {
        if (is_object($array)) {
            return isset($array[$key]);
        }
        return array_key_exists($key, $array);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array|object $array
     * @param  string|int|null $key
     * @param  mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default = NULL)
    {
        if (!self::accessible($array)) {
            return NULL;
        }

        if (is_null($key)) {
            return $array;
        }

        if (self::keyExists($key, $array)) {
            return $array[$key];
        }

        if (!self::hasDelimiter($key)) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (self::accessible($array) && self::keyExists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return NULL;
            }
        }
        return $array;
    }
}
