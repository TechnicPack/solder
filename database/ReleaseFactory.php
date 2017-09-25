<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ReleaseFactory
{
    public static function createForPackage($package, $overrides = [])
    {
        $release = factory(\App\Release::class)->make($overrides);
        $package->releases()->save($release);

        return $release;
    }
}
