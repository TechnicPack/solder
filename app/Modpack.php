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

use Carbon\Carbon;
use App\Traits\HasPrivacy;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Modpack.
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $overview
 * @property string $help
 * @property string $license
 * @property string $privacy
 * @property array $tags
 * @property string $website
 * @property string $icon
 * @property string $logo
 * @property string $background
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection $builds
 * @property-read string $tags_as_string
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack displayable()
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereOverview($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereHelp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereLicense($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack wherePrivacy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereTags($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereBackground($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modpack whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Modpack extends Model
{
    use HasSlug;
    use HasPrivacy;
    use UuidModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'overview',
        'help',
        'license',
        'privacy',
        'tags',
        'website',
    ];

    /**
     * The model's default attributes.
     *
     * @var array
     */
    protected $attributes = [
        'privacy' => Privacy::PRIVATE,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Related builds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions()
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    /**
     * Get tags as imploded string.
     */
    public function getTagsAsStringAttribute()
    {
        if ($this->tags === null) {
            return '';
        }

        return implode(', ', $this->tags);
    }
}
