<?php

namespace App;

use Carbon\Carbon;
use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string id
 * @property string version
 * @property \App\Asset archive
 * @property Collection builds
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property \App\Resource resource
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
     * The resource for this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Get the builds of this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function builds()
    {
        return $this->belongsToMany(Build::class);
    }

    /**
     * Get the models' archive.
     */
    public function archive()
    {
        return $this->morphOne(Asset::class, Asset::MORPH_NAME);
    }
}
