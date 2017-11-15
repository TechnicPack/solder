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

use App\Release;

class ReleasesController extends Controller
{
    /**
     * Delete a release.
     *
     * @param Release $release
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Release $release)
    {
        $this->authorize('delete', $release);

        $release->delete();

        return response(null, 204);
    }
}
