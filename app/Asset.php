<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Asset.
 *
 * @property string $id
 * @property string $version_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Version $version
 * @method static \Illuminate\Database\Query\Builder|\App\Asset whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Asset whereVersionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Asset whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Asset extends Model
{
    use UuidModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location',
        'filename',
        'filesize',
        'md5',
    ];

    /**
     * Belongs to a version.
     */
    public function version()
    {
        return $this->belongsTo(Version::class);
    }
}
