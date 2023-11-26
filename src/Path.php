<?php

namespace Cable8mm\QrImages;

class Path
{
    public static function root()
    {
        return getcwd().DIRECTORY_SEPARATOR;
    }

    public static function resources()
    {
        return self::root().'resources'.DIRECTORY_SEPARATOR;
    }

    public static function export()
    {
        return self::resources().'export'.DIRECTORY_SEPARATOR;
    }

    public static function images()
    {
        return self::resources().'images'.DIRECTORY_SEPARATOR;
    }
}
