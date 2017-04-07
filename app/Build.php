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

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Build extends Model
{
    const STATE_PUBLIC = 1;
    const STATE_DRAFT = 2;
    const STATE_PRIVATE = 3;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'arguments' => 'array',
    ];

    /**
     * It belongs to a Modpack.
     */
    public function modpack()
    {
        return $this->belongsTo(Modpack::class);
    }

    public function scopeWhereStatus(Builder $query, $status, $user = null)
    {
        $statusCollection = collect($status);

        return $query->where(function ($query) use ($statusCollection, $user) {
            if ($statusCollection->contains('public')) {
                $query->orWhere('status', self::STATE_PUBLIC);
            }

            if ($statusCollection->contains('private')) {
                $query->orWhere('status', self::STATE_PRIVATE);
            }

            if ($statusCollection->contains('draft')) {
                $query->orWhere('status', self::STATE_DRAFT);
            }

            if ($statusCollection->contains('authorized')) {
                $query->orWhere(function ($query) use ($statusCollection, $user) {
                    $query->where('status', self::STATE_PRIVATE)
                        ->WhereExists(function ($query) use ($user) {
                            $query->select(DB::raw(1))
                            ->from('modpack_user')
                            ->whereRaw('modpack_user.modpack_id = builds.modpack_id')
                            ->where('user_id', $user ? $user->id : null);
                        });
                });
            }
        });
    }

    public function addVersion(Version $version)
    {
        $this->versions()->attach($version);

        return $this;
    }

    /**
     * It belongs to many versions.
     */
    public function versions()
    {
        return $this->belongsToMany(Version::class);
    }

    public function toArray()
    {
        return [
            'minecraft' => $this->minecraft_version,
            'forge' => $this->arguments['forge_version'] ?? null,
            'java' => $this->arguments['java_version'] ?? null,
            'memory' => $this->arguments['java_memory'] ?? null,
            'mods' => $this->versions->map(function ($version) {
                return [
                    'name' => $version->resource->slug,
                    'version' => $version->version_number,
                    'md5' => $version->zip_md5,
                    'url' => $version->zip_url,
                ];
            })->all(),
        ];
    }
}
