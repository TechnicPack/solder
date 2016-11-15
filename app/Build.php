<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\UuidModelTrait;

class Build extends Model
{
    use UuidModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version',
        'published',
        'tags',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
        'published' => 'boolean',
    ];

    /**
     * Get the mod modpack this build is part of
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modpack()
    {
        return $this->belongsTo(App\Modpack::class);
    }

    /**
     * Get the mod releases attached to this build
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function releases()
    {
        return $this->belongsToMany(Release::class);
    }

    /**
     * Get the clients with permission on this build
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function clients()
    {
        return $this->morphToMany(Client::class, 'permission');
    }

    /**
     * Only published builds.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
