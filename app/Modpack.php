<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Alsofronie\Uuid\UuidModelTrait;

/**
 * @property string id
 * @property string slug
 * @property string name
 * @property boolean published
 * @property \App\Asset icon
 * @property \App\Asset logo
 * @property \App\Asset background
 * @property \App\Build latest
 * @property \App\Build promoted
 * @property Collection clients
 * @property Collection builds
 *
 * @method Builder published() query scope where modpack is published
 * @method Builder permitted(mixed $client) Query scope where $client is permitted to see Modpack
 */
class Modpack extends Model
{
    use HasSlug;
    use UuidModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'published',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'published' => 'boolean',
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
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the builds for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    /**
     * Get the models' icon.
     */
    public function icon()
    {
        return $this->morphOne(ModpackIcon::class, Asset::MORPH_NAME);
    }

    /**
     * Get the models' icon.
     */
    public function logo()
    {
        return $this->morphOne(ModpackLogo::class, Asset::MORPH_NAME);
    }

    /**
     * Get the models' icon.
     */
    public function background()
    {
        return $this->morphOne(ModpackBackground::class, Asset::MORPH_NAME);
    }

    /**
     * Get the promoted build for the modpack.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promoted()
    {
        return $this->belongsTo(Build::class, 'promoted_build_id');
    }

    /**
     * Get the clients with permission on this build.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Get the latest build for the modpack.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function latest()
    {
        return $this->belongsTo(Build::class, 'latest_build_id');
    }

    /**
     * Only published modpacks.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    /**
     * Return results where the given client has permission.
     *
     * @param Builder $query
     * @param Client $client
     * @return Builder
     */
    public function scopePermitted($query, $client)
    {
        if ($client === null) {
            return $query->where('published', true);
        }

        if ($client->is_global) {
            return $query;
        }

        return $query
            ->where('published', true)
            ->orWhere('id', $client->modpacks()->pluck('id'));
    }
}
