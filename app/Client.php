<?php

namespace App;

use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Only global clients.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
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
