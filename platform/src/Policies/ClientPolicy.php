<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the client index.
     *
     * @return mixed
     */
    public function list()
    {
        return true;
    }

    /**
     * Determine whether the user can create clients.
     *
     * @return mixed
     */
    public function create()
    {
        return true;
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @return mixed
     */
    public function delete()
    {
        return true;
    }
}
