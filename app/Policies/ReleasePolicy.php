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
use App\Release;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReleasePolicy
{
    use HandlesAuthorization;

    /**
     * Check for authorization before the intended policy method is actually called.
     *
     * @param \App\User  $user
     *
     * @return bool
     */
    public function before($user)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function view(User $user, Release $release)
    {
        //
    }

    /**
     * Determine whether the user can create releases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('tag', 'update-package')->exists();
    }

    /**
     * Determine whether the user can update the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function update(User $user, Release $release)
    {
        //
    }

    /**
     * Determine whether the user can delete the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function delete(User $user, Release $release)
    {
        return $user->roles()->where('tag', 'update-package')->exists();
    }
}
