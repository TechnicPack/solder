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

use App\User;
use App\Modpack;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModpackPolicy
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
     * Determine whether the user can view the modpack.
     *
     * @param  \App\User  $user
     * @param  \App\Modpack  $modpack
     * @return mixed
     */
    public function view(User $user, Modpack $modpack)
    {
        //
    }

    /**
     * Determine whether the user can create modpacks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('tag', 'create-modpack')->exists();
    }

    /**
     * Determine whether the user can update the modpack.
     *
     * @param  \App\User  $user
     * @param  \App\Modpack  $modpack
     * @return mixed
     */
    public function update(User $user, Modpack $modpack)
    {
        return $user->roles()->where('tag', 'update-modpack')->exists();
    }

    /**
     * Determine whether the user can delete the modpack.
     *
     * @param  \App\User  $user
     * @param  \App\Modpack  $modpack
     * @return mixed
     */
    public function delete(User $user, Modpack $modpack)
    {
        //
    }
}
