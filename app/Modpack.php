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
    protected $guarded = [];

    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

    public function scopePublic($query)
    {
        return $query->where('status', 'public');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePrivate($query)
    {
        return $query->where('status', 'private');
    }

    public function getPromotedBuildAttribute()
    {
        return optional(Build::where('id', $this->promoted_build_id)->first());
    }

    public function getLatestBuildAttribute()
    {
        return optional(Build::where('id', $this->latest_build_id)->first());
    }
}
