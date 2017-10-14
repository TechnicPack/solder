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
use App\Build;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildPolicy
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
     * Determine whether the user can view the build.
     *
     * @param  \App\User  $user
     * @param  \App\Build  $build
     * @return mixed
     */
    public function view(User $user, Build $build)
    {
        //
    }

    /**
     * Determine whether the user can create builds.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('tag', 'update-modpack')->exists();
    }

    /**
     * Determine whether the user can update the build.
     *
     * @param  \App\User  $user
     * @param  \App\Build  $build
     * @return mixed
     */
    public function update(User $user, Build $build)
    {
        //
    }

    /**
     * Determine whether the user can delete the build.
     *
     * @param  \App\User  $user
     * @param  \App\Build  $build
     * @return mixed
     */
    public function delete(User $user, Build $build)
    {
        return $user->roles()->where('tag', 'update-modpack')->exists();
    }
}
