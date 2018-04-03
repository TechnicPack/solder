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

class Bundle extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'build_release';

    /**
     * Bundles belong to a build.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function build()
    {
        return $this->belongsTo(Build::class);
    }
}
