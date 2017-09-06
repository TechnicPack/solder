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
    public function releases()
    {
        return $this->belongsToMany(Release::class);
    }

    public function scopePublic($query)
    {
        return $query->where('status', 'public');
    }
}
