<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform;

use App\Modpack;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A Client belongs to many Modpacks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modpacks()
    {
        return $this->belongsToMany(Modpack::class);
    }
}
