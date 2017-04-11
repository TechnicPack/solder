<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Facades;

use App\UuidGenerator;
use Illuminate\Support\Facades\Facade;

class Uuid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return UuidGenerator::class;
    }
}
