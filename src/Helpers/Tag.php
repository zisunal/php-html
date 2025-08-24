<?php

namespace Zisunal\PhpHtml\Helpers;

class Tag {
    protected static array $staticCallable = [
        'br', 'hr', 'wbr', 'list', 'table', 'p', 'a', 'img', 'div', 'iframe', 'span', 'button', 
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'i'
    ];
    private static array $alternatives = [
        "ul" => "list",
        "ol" => "list"
    ];

    public static function all(): array
    {
        return static::$tags;
    }

    public static function isValid(string $tag): bool
    {
        return in_array($tag, static::$tags);
    }

    public static function count(): int
    {
        return count(static::$tags);
    }

    public static function isSCValid(string $name): bool
    {
        return in_array($name, static::$staticCallable);
    }

    public static function hasAlt(string $name): bool
    {
        return array_key_exists($name, static::$alternatives);
    }

    public static function getAlt(string $name): ?string
    {
        return static::$alternatives[$name] ?? null;
    }

}