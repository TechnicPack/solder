<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
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
     * @param $releaseId
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($releaseId)
    {
        $release = Release::findOrFail($releaseId);

        $this->authorize('delete', $release);

        $release->delete();

        return response(null, 204);
    }
}
