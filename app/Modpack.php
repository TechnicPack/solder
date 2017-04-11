<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use App\Facades\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Modpack extends Model
{
    const STATUS_PUBLIC = 1;
    const STATUS_PRIVATE = 2;
    const STATUS_UNLISTED = 3;

    protected $statuses = [
        self::STATUS_PRIVATE => 'private',
        self::STATUS_PUBLIC => 'public',
        self::STATUS_UNLISTED => 'unlisted',
    ];

    protected $guarded = [];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate();
        });
    }

    /**
     * Related builds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function authorizeUser(User $user)
    {
        $this->users()->attach($user);

        return $this;
    }

    public function scopeWhereStatus(Builder $query, $status, $user = null)
    {
        $statusCollection = collect($status);

        return $query->where(function ($query) use ($statusCollection, $user) {
            if ($statusCollection->contains('public')) {
                $query->orWhere('status', self::STATUS_PUBLIC);
            }

            if ($statusCollection->contains('unlisted')) {
                $query->orWhere('status', self::STATUS_UNLISTED);
            }

            if ($statusCollection->contains('private')) {
                $query->orWhere('status', self::STATUS_PRIVATE);
            }

            if ($statusCollection->contains('authorized')) {
                $query->orWhereExists(function ($query) use ($user) {
                    $query->select(DB::raw(1))
                        ->from('modpack_user')
                        ->whereRaw('modpack_user.modpack_id = modpacks.id')
                        ->where('user_id', $user ? $user->id : null);
                });
            }
        });
    }

    public function getStatusAsStringAttribute()
    {
        return $this->statuses[$this->status];
    }

    public function setStatusAsStringAttribute($value)
    {
        $this->status = collect($this->statuses)->search($value);
    }

    public function getLinkSelfAttribute()
    {
        return \Config::get('app.url')."/api/modpacks/{$this->id}";
    }

    public function toArray()
    {
        return [
            'name' => $this->slug,
            'display_name' => $this->name,
            'recommended' => $this->recommended,
            'latest' => $this->latest,
        ];
    }
}
