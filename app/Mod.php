<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Alsofronie\Uuid\UuidModelTrait;

/**
 * @property string id
 * @property string name
 * @property string slug
 * @property string author
 * @property string description
 * @property string link
 * @property Collection releases
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Mod extends Model
{
    use HasSlug;
    use UuidModelTrait;

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
        'link',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    /**
     * Get the packages for this mod.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function releases()
    {
        return $this->hasMany(Release::class);
    }
}
