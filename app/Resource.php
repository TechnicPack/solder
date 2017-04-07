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

class Resource extends Model
{
    /**
     * Has manny versions.
     */
    public function versions()
    {
        return $this->hasMany(Version::class);
    }
}
