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
    /**
     * A Build contains many Releases of various Packages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function releases()
    {
        return $this->belongsToMany(Release::class);
    }

    /**
     * Filter query results to public builds, private builds
     * that have been authorized with the provided client token
     * and all private builds with a valid provided api key.
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
            $query->where('status', 'public')
                ->orWhere(function ($query) use ($apiToken, $clientToken) {
                    $query->where('status', 'private')
                        ->whereIn('modpack_id', function ($query) use ($clientToken) {
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
