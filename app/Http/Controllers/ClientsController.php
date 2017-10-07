<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers;

class ClientsController extends Controller
{
    /**
     * List all the clients associated with the authenticated user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('profile.clients', [
            'clients' => request()->user()->launchers()->orderBy('title')->get(),
        ]);
    }

    /**
     * Create a new Launcher Client tied to the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $client = request()->validate([
            'title' => ['required'],
            'token' => ['required', 'unique:clients'],
        ]);

        request()->user()->launchers()->create($client);

        return redirect('/profile/clients');
    }

    /**
     * Delete the client.
     *
     * @param $clientId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($clientId)
    {
        request()->user()
            ->launchers()
            ->where('id', $clientId)
            ->firstOrFail()
            ->delete();

        return redirect('/profile/clients');
    }
}
