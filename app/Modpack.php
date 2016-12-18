<?php

namespace App;

use Carbon\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string id
 * @property string slug
 * @property string name
 * @property \App\Asset icon
 * @property \App\Asset logo
 * @property \App\Asset background
 * @property \App\Build latest
 * @property \App\Build promoted
 * @property Collection clients
 * @property Collection builds
 * @property Carbon published_at
 * @property Carbon created_at
 * @property Carbon updated_at
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
        'published_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class);
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
     * Where modpack is published.
     *
     * @param Builder $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopePublished(Builder $query)
    {
        return $query->whereDate('published_at', '<', Carbon::now());
    }

    /**
     * Return results where the given client has permission.
     *
     * @param Builder $query
     * @param Client $client
     *
     * @return Builder
     */
    public function scopeWhereAllowed($query, $client)
    {
        if ($client === null) {
            return $this->scopePublished($query);
        }

        if ($client->is_global) {
            return $query;
        }

        return $query
            ->whereIn('id', $client->modpacks()->pluck('id'))
            ->orWhere('published_at', '<', Carbon::now());
    }

    /**
     * Give the client permission to view this modpack.
     *
     * @param Client $client
     *
     * @return $this
     */
    public function allow(Client $client)
    {
        $this->clients()->save($client);

        return $this;
    }

    /**
     * Check if the client is allowed to view the modpack.
     *
     * @param Client $client
     *
     * @return bool
     */
    public function allowed($client)
    {
        if ($this->published_at !== null && $this->published_at->isPast()) {
            return true;
        }

        if ($client === null) {
            return false;
        }

        if ($client->is_global) {
            return true;
        }

        if ($this->clients()->find($client->id)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the client is not allowed to view the modpack.
     *
     * @param Client $client
     *
     * @return bool
     */
    public function disallowed($client)
    {
        return ! $this->allowed($client);
    }
}
