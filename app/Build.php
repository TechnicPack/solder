<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\UuidModelTrait;

/**
 * @property string id
 * @property string version
 * @property bool published
 * @property array tags
 * @property \App\Modpack modpack
 * @property Collection releases
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method Builder published() query scope for published builds
 */
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
     * Get the mod modpack this build is part of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modpack()
    {
        return $this->belongsTo(App\Modpack::class);
    }

    /**
     * Get the mod releases attached to this build.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function releases()
    {
        return $this->belongsToMany(Release::class);
    }

    /**
     * Get the clients with permission on this build.
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
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
