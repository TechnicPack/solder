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
 * App\Version.
 *
 * @property string $id
 * @property string $resource_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Asset[] $assets
 * @property-read \App\Resource $resource
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Build[] $builds
 * @method static \Illuminate\Database\Query\Builder|\App\Version whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Version whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Version whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Version whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Version extends Model
{
    use UuidModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version',
    ];

    /**
     * Has manny assets.
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Belongs to a resource.
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Belongs to many builds.
     */
    public function builds()
    {
        return $this->belongsToMany(Build::class);
    }
}
