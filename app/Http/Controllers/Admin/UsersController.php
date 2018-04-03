<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Browse all users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('settings.users', [
            'users' => User::orderBy('username')->get(),
        ]);
    }

    /**
     * Store a new user in the system.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize('create', User::class);

        request()->validate([
            'username' => ['required', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        User::create([
            'username' => request('username'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'is_admin' => request('is_admin') == 'on',
        ]);

        return redirect('/settings/users');
    }

    /**
     * Update a users details.
     *
     * @param $userId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($userId)
    {
        $user = User::findOrFail($userId);

        $this->authorize('update', $user);

        request()->validate([
            'username' => ['required', Rule::unique('users')->ignore($userId)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'password' => ['nullable', 'min:6'],
        ]);

        $user->fill([
            'username' => request('username'),
            'email' => request('email'),
            'is_admin' => request('is_admin', false),
            'password' => request('password') !== null ? bcrypt(request('password')) : $user->password,
        ])->save();

        return redirect('/settings/users/');
    }

    /**
     * Delete a user.
     *
     * @param $userId
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);

        $this->authorize('delete', $user);

        $user->delete();

        return redirect('/settings/users');
    }
}
