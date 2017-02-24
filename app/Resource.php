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

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Resource.
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $author
 * @property string $description
 * @property string $website
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Version[] $versions
 * @method static \Illuminate\Database\Query\Builder|\App\Resource whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Resource whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Resource whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Resource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resource extends Model
{
    use UuidModelTrait;
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'author',
        'description',
        'website',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions()
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Has manny versions.
     */
    public function versions()
    {
        return $this->hasMany(Version::class);
    }
}
