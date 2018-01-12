<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use App\Build;
use App\Modpack;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class ModpackController extends Controller
{
    /**
     * Return a JSON response listing all modpacks the requester has access to.
     * If a valid API key is provided in the query string as k={key} then all
     * public and private modpacks and builds. If a valid Client token is
     * provided in the query string as cid={token} then all public modpacks and
     * builds, and any private modpacks and builds that the client has been
     * authorized for will be returned.
     *
     * Additional details can be returned by placing include=full in the query
     * string.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $modpacks = Modpack::whereToken(request()->get('k'), request()->get('cid'))->get();

        $builds = Build::whereIn('modpack_id', $modpacks->pluck('id'))
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->get();

        return response()->json([
            'modpacks' => $modpacks->keyBy('slug')->transform(function ($modpack) use ($builds) {
                if (request()->query('include') == 'full') {
                    return $this->formatModpackWithBuilds(
                        $modpack,
                        $builds->filter(function ($build) use ($modpack) {
                            return $build->modpack_id == $modpack->id;
                        })
                    );
                }

                return $modpack->name;
            }),
            'mirror_url' => config('app.repo'),
        ]);
    }

    /**
     * Return a JSON response containing details of a specific Modpack and
     * list all builds. As with the index method, an API key (k={key}) or
     * Client token (cid={token}) can be appended to the query string to
     * provide access to private modpacks and builds as authorized and
     * required.
     *
     * @param $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        $modpack = Modpack::where('slug', $slug)
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->first();

        if ($modpack === null) {
            return response()->json([
                'error' => 'Modpack does not exist',
            ], 404);
        }

        $builds = Build::whereModpackId($modpack->id)
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->get();

        return response()->json($this->formatModpackWithBuilds($modpack, $builds));
    }

    /**
     * Return modpack and build details formatted for API response.
     *
     * @param Modpack $modpack
     * @param Collection $builds
     *
     * @return array
     */
    private function formatModpackWithBuilds($modpack, $builds)
    {
        return [
            'name' => $modpack->slug,
            'display_name' => $modpack->name,
            'recommended' => $modpack->recommended_build->version,
            'latest' => $modpack->latest_build->version,
            'builds' => $builds->pluck('version'),
        ];
    }
}
