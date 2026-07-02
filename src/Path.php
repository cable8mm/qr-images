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
        $resourcesPath = Config::get('paths.resources', 'resources');
        
        if (self::isAbsolutePath($resourcesPath)) {
            return rtrim($resourcesPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        }
        
        return self::root().$resourcesPath.DIRECTORY_SEPARATOR;
    }

    public static function export(): string
    {
        $exportPath = Config::get('paths.export', 'resources/export');
        
        if (self::isAbsolutePath($exportPath)) {
            $fullPath = rtrim($exportPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        } else {
            $fullPath = self::root().$exportPath.DIRECTORY_SEPARATOR;
        }
        
        if (!is_dir($fullPath)) {
            throw new QrImagesRuntimeException(sprintf('Export directory does not exist: %s', $fullPath));
        }
        
        if (!is_writable($fullPath)) {
            throw new QrImagesRuntimeException(sprintf('Export directory is not writable: %s', $fullPath));
        }
        
        return $fullPath;
    }

    public static function images(): string
    {
        $imagesPath = Config::get('paths.images', 'resources/images');
        
        if (self::isAbsolutePath($imagesPath)) {
            $fullPath = rtrim($imagesPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        } else {
            $fullPath = self::root().$imagesPath.DIRECTORY_SEPARATOR;
        }
        
        if (!is_dir($fullPath)) {
            throw new QrImagesRuntimeException(sprintf('Images directory does not exist: %s', $fullPath));
        }
        
        if (!is_writable($fullPath)) {
            throw new QrImagesRuntimeException(sprintf('Images directory is not writable: %s', $fullPath));
        }
        
        return $fullPath;
    }

    public static function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/') || 
               (strlen($path) >= 3 && 
                ctype_alpha($path[0]) && 
                $path[1] === ':' && 
                $path[2] === '\\');
    }
}
