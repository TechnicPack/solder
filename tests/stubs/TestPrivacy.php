<?php
/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Traits\HasPrivacy;

class TestPrivacy extends \Illuminate\Database\Eloquent\Model
{
    use HasPrivacy;
    protected $attributes = ['privacy'];
    protected $fillable = ['privacy'];
    protected $table = null;
}
