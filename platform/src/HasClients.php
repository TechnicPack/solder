<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

trait HasClients
{
    /**
     * Model clients.
     */
    public function clients()
    {
        return $this->morphToMany(Client::class, 'exposes', 'client_exposes');
    }

    /**
     * Model has at least one client.
     *
     * @return bool
     */
    public function hasClients()
    {
        return $this->clients()->count() > 0;
    }

    /**
     * Model belongs to the given client.
     *
     * @param int|Client $client
     * @return bool
     */
    public function knowsClient($client)
    {
        if (is_int($client)) {
            $client = (object) ['id' => $client];
        }

        return $this->clients()->where('id', $client->id)->exists();
    }

    /**
     * Attach the given client to the model.
     *
     * @param Client $client
     */
    public function attachClient($client)
    {
        $this->clients()->attach($client);
    }

    /**
     * Detach the given client from the model.
     *
     * @param Client $client
     */
    public function detachClient($client)
    {
        $this->clients()->detach($client);
    }

    /**
     * Scope results to a specific client.
     *
     * @param Builder $query
     * @param $client
     * @return Builder
     */
    public function scopeForClient($query, $client)
    {
        if (is_int($client)) {
            $client = (object) ['id' => $client];
        }

        return $query->whereHas('clients', function ($query) use ($client) {
            $query->where('id', $client->id);
        });
    }

    /**
     * Scope results to a specific client token.
     *
     * @param Builder $query
     * @param $clientToken
     * @return Builder
     */
    public function scopeForClientToken($query, $clientToken)
    {
        return $query->whereHas('clients', function ($query) use ($clientToken) {
            $query->where('token', $clientToken);
        });
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
