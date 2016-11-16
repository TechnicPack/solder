<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\UuidModelTrait;


class Client extends Model
{
    use UuidModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
    ];

    /**
     * Only global clients.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /**
     * Get the modpacks this client has permissions on.
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphToMany
     */
    public function modpacks()
    {
        return $this->belongsToMany(Modpack::class);
    }

    /**
     * Checks if the provided client has permission to a modpack.
     *
     * @param null|\App\Modpack $modpack
     * @return bool
     */
    public function isPermitted($modpack)
    {
        if ($modpack === null) {
            return false;
        }

        if ($modpack->published) {
            return true;
        }

        if ($this->is_global) {
            return true;
        }

        return $this->modpacks()->where('modpack_id', $modpack->id)->count() > 0;
    }
}
