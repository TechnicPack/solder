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

use Platform\Client;
use App\Http\Controllers\Controller;

class ClientsController extends Controller
{
    /**
     * List all the clients.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', Client::class);

        return view('settings.clients', [
            'clients' => Client::orderBy('title')->get(),
        ]);
    }

    /**
     * Create a new Launcher Client.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize('create', Client::class);

        $client = request()->validate([
            'title' => ['required'],
            'token' => ['required', 'unique:clients'],
        ]);

        Client::create($client);

        return redirect('/settings/clients');
    }

    /**
     * Delete the client.
     *
     * @param $clientId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($clientId)
    {
        $client = Client::find($clientId);

        $this->authorize('delete', $client);

        $client->delete();

        return redirect('/settings/clients');
    }
}
