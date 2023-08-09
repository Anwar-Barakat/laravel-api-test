<?php

namespace App\Helper\Routes;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteHelper
{
    public static function includeRouteFiles(string $folder): void
    {
        // iterate thru the v1 folder recursively
        $dirIterator    = new RecursiveDirectoryIterator($folder);

        $it = new RecursiveIteratorIterator($dirIterator);
        while ($it->valid()) {
            if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                require $it->key();
            }
            $it->next();
        }
    }
}
