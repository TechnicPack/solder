<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
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
     * Determine whether the user can create packages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('tag', 'create-package')->exists();
    }

    /**
     * Determine whether the user can update the package.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->roles()->where('tag', 'update-package')->exists();
    }

    /**
     * Determine whether the user can delete the package.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->roles()->where('tag', 'delete-package')->exists();
    }
}
