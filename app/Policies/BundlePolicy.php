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
use Illuminate\Auth\Access\HandlesAuthorization;

class BundlePolicy
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
     * Determine whether the user can create bundles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('tag', 'update-modpack')->exists();
    }

    /**
     * Determine whether the user can delete the bundle.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->roles()->where('tag', 'update-modpack')->exists();
    }
}
