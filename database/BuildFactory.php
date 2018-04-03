<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BuildFactory
{
    public static function createForModpack($modpack, $overrides = [], $states = 'public')
    {
        $build = factory(\App\Build::class)->states($states)->make($overrides);
        $modpack->builds()->save($build);

        return $build;
    }
}
