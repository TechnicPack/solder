<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use TechnicPack\LauncherApi\Build as PlatformBuild;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Build extends Model implements PlatformBuild
{
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

    /**
     * Scope modpacks to models that are public.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublic(Builder $query)
    {
        return $query;
    }

    /**
     * Scope modpacks to models not flagged as 'hidden'.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePrivate(Builder $query)
    {
        return $query;
    }

    /**
     * The mods that make up this build.
     *
     * @return HasMany|BelongsToMany
     */
    public function mods()
    {
        return $this->belongsToMany(Release::class);
    }
}
