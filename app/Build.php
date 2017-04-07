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

class Build extends Model
{


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'arguments' => 'array',
    ];

    /**
     * It belongs to a Modpack.
     */
    public function modpack()
    {
        return $this->belongsTo(Modpack::class);
    }

    public function versions()
    {
    }

    public function getResourceCountAttribute()
    {
    }

    /**
     */
    public function getIsLatestAttribute()
    {
        return $this->modpack->latestBuild->id == $this->id;
    }

    public function promote()
    {
    }
}
