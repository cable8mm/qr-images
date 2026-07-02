<?php

namespace Cable8mm\QrImages;

use Cable8mm\QrImages\Exceptions\QrImagesRuntimeException;

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
        $exportPath = self::resources().'export'.DIRECTORY_SEPARATOR;

        if (! is_dir($exportPath)) {
            throw new QrImagesRuntimeException(sprintf('Export directory does not exist: %s', $exportPath));
        }

        if (! is_writable($exportPath)) {
            throw new QrImagesRuntimeException(sprintf('Export directory is not writable: %s', $exportPath));
        }

        return $exportPath;
    }

    public static function images(): string
    {
        $imagesPath = self::resources().'images'.DIRECTORY_SEPARATOR;

        if (! is_dir($imagesPath)) {
            throw new QrImagesRuntimeException(sprintf('Images directory does not exist: %s', $imagesPath));
        }

        if (! is_writable($imagesPath)) {
            throw new QrImagesRuntimeException(sprintf('Images directory is not writable: %s', $imagesPath));
        }

        return $imagesPath;
    }
}
