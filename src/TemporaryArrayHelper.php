<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

/**
 * Class will be removed after https://github.com/yiisoft/arrays/pull/80
 */
final class TemporaryArrayHelper
{
    private static function keyExists(array $array, $key, bool $caseSensitive = true): bool
    {
        if (is_array($key)) {
            if (count($key) === 1) {
                return self::rootKeyExists($array, end($key), $caseSensitive);
            }

            foreach (self::getExistsKeys($array, array_shift($key), $caseSensitive) as $existKey) {
                /** @var mixed */
                $array = self::getRootValue($array, $existKey, null);
                if (is_array($array) && self::keyExists($array, $key, $caseSensitive)) {
                    return true;
                }
            }

            return false;
        }

        return self::rootKeyExists($array, $key, $caseSensitive);
    }

    private static function rootKeyExists(array $array, $key, bool $caseSensitive): bool
    {
        $key = (string)$key;

        if ($caseSensitive) {
            return array_key_exists($key, $array);
        }

        foreach (array_keys($array) as $k) {
            if (strcasecmp($key, (string)$k) === 0) {
                return true;
            }
        }

        return false;
    }

    private static function getExistsKeys(array $array, $key, bool $caseSensitive): array
    {
        $key = (string)$key;

        if ($caseSensitive) {
            return [$key];
        }

        return array_filter(
            array_keys($array),
            fn ($k) => strcasecmp($key, (string)$k) === 0
        );
    }

    public static function pathExists(
        array $array,
        $path,
        bool $caseSensitive = true,
        string $delimiter = '.'
    ): bool {
        return self::keyExists($array, self::parsePath($path, $delimiter), $caseSensitive);
    }

    private static function getRootValue($array, $key, $default)
    {
        if (is_array($array)) {
            $key = self::normalizeArrayKey($key);
            return array_key_exists($key, $array) ? $array[$key] : $default;
        }

        if (is_object($array)) {
            try {
                return $array::$$key;
            } catch (\Throwable $e) {
                // this is expected to fail if the property does not exist, or __get() is not implemented
                // it is not reliably possible to check whether a property is accessible beforehand
                return $array->$key;
            }
        }

        return $default;
    }

    private static function normalizeArrayKey($key): string
    {
        return is_float($key) ? \Yiisoft\Strings\NumericHelper::normalize($key) : (string)$key;
    }

    private static function parsePath($path, string $delimiter)
    {
        if (is_string($path)) {
            return explode($delimiter, $path);
        }
        if (is_array($path)) {
            $newPath = [];
            foreach ($path as $key) {
                if (is_string($key) || is_array($key)) {
                    $newPath = array_merge($newPath, self::parsePath($key, $delimiter));
                } else {
                    $newPath[] = $key;
                }
            }
            return $newPath;
        }
        return $path;
    }
}
