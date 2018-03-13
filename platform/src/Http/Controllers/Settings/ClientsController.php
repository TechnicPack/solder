<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Http\Controllers\Settings;

use Exception;
use Platform\Client;
use App\Http\Controllers\Controller;
use Platform\Http\Resources\ClientResource;

class ClientsController extends Controller
{
    /**
     * List all the clients.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('clients.list', Client::class);

        return ClientResource::collection(Client::all());
    }

    /**
     * Create a new Launcher Client.
     *
     * @return ClientResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize('clients.create', Client::class);

        $this->validate(request(), [
            'title' => ['required', 'unique:clients'],
            'token' => ['required', 'unique:clients'],
        ]);

        $client = Client::create([
            'title' => request('title'),
            'token' => request('token'),
        ]);

        return new ClientResource($client);
    }

    /**
     * Delete the client.
     *
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Client $client)
    {
        $this->authorize('clients.delete', $client);

        try {
            $client->delete();
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }

        return response(null, 204);
    }
}
