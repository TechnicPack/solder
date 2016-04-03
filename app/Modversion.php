<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modversion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'mod_id', 'version', 'md5',
    ];

    /**
     * A modversion belongs to a mod.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }

    /**
     * A modversion belongs to many builds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function builds()
    {
        return $this->belongsToMany(Build::class);
    }

    /**
     * Add a build to the modversion.
     *
     * @param Build $build
     *
     * @return Modversion
     */
    public function addBuild(Build $build)
    {
        return $this->builds()->create($build);
    }
}
