<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Http\Controllers\Api;

use App\Build;
use App\Modpack;
use Platform\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Platform\Http\Resources\Api\BuildResource;

class ModpackBuildController extends Controller
{
    /**
     * ModpackBuildController constructor.
     */
    public function __construct()
    {
        Resource::withoutWrapping();
    }

    /**
     * Return a JSON response containing the releases for a given
     * Modpack slug and Build version.
     *
     * @param string $slug
     * @param string $version
     *
     * @return BuildResource
     */
    public function show($slug, $version)
    {
        $modpack = Modpack::with('builds')
            ->where('slug', $slug)
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->firstOrFail();

        $build = Build::with('releases', 'releases.package')
            ->whereVersion($version)
            ->whereModpackId($modpack->id)
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->firstOrFail();

        return new BuildResource($build);
    }
}
