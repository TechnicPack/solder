<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
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
