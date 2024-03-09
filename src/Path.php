<?php

namespace Cable8mm\QrImages;

class Path
{
    public static function root(): string
    {
        return getcwd().DIRECTORY_SEPARATOR;
    }

    public static function resources(): string
    {
        return self::root().'resources'.DIRECTORY_SEPARATOR;
    }

    public static function export(): string
    {
        return self::resources().'export'.DIRECTORY_SEPARATOR;
    }

    public static function images(): string
    {
        return self::resources().'images'.DIRECTORY_SEPARATOR;
    }
}
