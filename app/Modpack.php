<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modpack extends Model
{

    /**
     * Related builds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    public function getPromotedBuildVersionAttribute()
    {
    }

    public function getLatestBuildVersionAttribute()
    {

    }

    public function getSlugOptions()
    {


    }

    public function delete()
    {
    }
}
