<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Traits;

use Platform\Client;
use Illuminate\Support\Facades\DB;

trait ModpackPlatformSupport
{
    /**
     * A Modpack has many authorized clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

    /**
     * Filter query results to public modpack, private modpacks
     * that have been authorized with the provided client token
     * and all private modpacks with a valid provided api key.
     *
     * @param Builder $query
     * @param string|null $apiToken
     * @param string|null $clientToken
     *
     * @return Builder
     */
    public function scopeWhereToken($query, $apiToken, $clientToken)
    {
        return $query->where(function ($query) use ($apiToken, $clientToken) {
            /* @var Builder $query */
            $query->where('status', 'public')
                ->orWhere(function ($query) use ($apiToken, $clientToken) {
                    $query->where('status', 'private')
                        ->whereIn('id', function ($query) use ($clientToken) {
                            return $query->select('modpack_id')
                                ->from('client_modpack')
                                ->join('clients', 'client_modpack.client_id', '=', 'clients.id')
                                ->where('token', $clientToken);
                        });
                })
                ->orWhere(function ($query) use ($apiToken) {
                    $query->where('status', 'private')
                        ->whereExists(function ($query) use ($apiToken) {
                            $query->select(DB::raw(1))
                                ->from('keys')
                                ->where('token', $apiToken);
                        });
                });
        });
    }
}
