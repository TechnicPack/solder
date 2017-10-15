<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Policies;

use App\Key;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KeyPolicy
{
    use HandlesAuthorization;

    /**
     * Check for authorization before the intended policy method is actually called.
     *
     * @param \App\User  $user
     * @param $ability
     *
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the key index.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->roles()->where('tag', 'manage-keys')->exists();
    }

    /**
     * Determine whether the user can view the key.
     *
     * @param  \App\User  $user
     * @param  \App\Key  $key
     * @return mixed
     */
    public function view(User $user, Key $key)
    {
        //
    }

    /**
     * Determine whether the user can create keys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('tag', 'manage-keys')->exists();
    }

    /**
     * Determine whether the user can update the key.
     *
     * @param  \App\User  $user
     * @param  \App\Key  $key
     * @return mixed
     */
    public function update(User $user, Key $key)
    {
        //
    }

    /**
     * Determine whether the user can delete the key.
     *
     * @param  \App\User  $user
     * @param  \App\Key  $key
     * @return mixed
     */
    public function delete(User $user, Key $key)
    {
        //
    }
}
