<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\UuidModelTrait;

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
     * Get the builds of this release
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
