<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\UuidModelTrait;

/**
 * @property string id
 * @property string name
 * @property string token
 * @property bool is_global
 *
 * @method Builder global() query scope where client is_global

 */
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modpacks()
    {
        return $this->belongsToMany(Modpack::class);
    }
}
