<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Build
 *
 * @package App
 */
class Build extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'modpack_id', 'version', 'minecraft', 'published', 'private', 'min_java', 'min_memory',
    ];

    /**
     * Flag upstream models as updated when we modify this one
     *
     * @var array
     */
    protected $touches = ['modpack'];

    /**
     * A build belongs to a modpack
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modpack()
    {
        return $this->belongsTo(Modpack::class);
    }

    /**
     * A build belongs to many modversions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modversions()
        {
        return $this->belongsToMany(Modversion::class);
    }

    /**
     * Add a modversion to the build
     * 
     * @param Modversion $modversion
     * @return Model
     */
    public function addModversion(Modversion $modversion) {
        return $this->modversions()->save($modversion);
    }

}
