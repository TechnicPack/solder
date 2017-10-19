<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Role;
use App\User;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    /**
     * List all users and associated roles.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('manage', User::class);

        return view('settings.permissions', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Batch update user roles.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update()
    {
        $this->authorize('manage', User::class);

        foreach (request('users') as $userId => $roles) {
            User::where('id', $userId)->first()->roles()->sync($roles);
        }

        return redirect('settings/permissions');
    }
}
