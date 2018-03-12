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
use Platform\Traits\BuildPlatformSupport;

class Build extends Model
{
    use BuildPlatformSupport;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A Build contains many Releases of various Packages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function releases()
    {
        return $this->belongsToMany(Release::class);
    }

    /**
     * A Build belongs to a Modpack.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modpack()
    {
        return $this->belongsTo(Modpack::class);
    }

    /**
     * Get created date formatted for humans.
     *
     * @return string
     */
    public function getCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the most recent builds and associated modpacks.
     *
     * @param $count
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function recent($count)
    {
        return self::latest()->take($count)->with('modpack')->get();
    }
}
