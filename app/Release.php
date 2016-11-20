<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\UuidModelTrait;

/**
 * @property string id
 * @property string version
 * @property \App\Asset archive
 * @property Collection builds
 */
class Release extends Model
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
     * The mod for this release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }

    /**
     * Get the builds of this release.
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
