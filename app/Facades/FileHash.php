<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Facades;

use App\FileHashGenerator;
use Illuminate\Support\Facades\Facade;

class FileHash extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FileHashGenerator::class;
    }

    protected static function getMockableClass()
    {
        return static::getFacadeAccessor();
    }
}
