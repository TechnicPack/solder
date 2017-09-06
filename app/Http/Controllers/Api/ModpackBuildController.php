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

use App\Key;
use App\Build;
use App\Client;
use App\Modpack;
use App\Http\Controllers\Controller;

class ModpackBuildController extends Controller
{
    public function show($slug, $build)
    {
        if ($this->requestHasValidKey()) {
            $modpack = Modpack::whereIn('status', ['public','private'])
                ->whereSlug($slug)
                ->with('builds')
                ->first();
        } elseif (request()->has('cid')) {
            $modpack = Modpack::public()
                ->orWhere(function ($query) {
                    $client = Client::where('token', request()->get('cid'))->first();

                    $query->where('status', 'private')
                        ->whereIn('id', $client->modpacks->pluck('id'));
                })
                ->whereSlug($slug)
                ->with('builds')
                ->first();
        } else {
            $modpack = Modpack::public()
                ->whereSlug($slug)
                ->with('builds')
                ->first();
        }

        if ($modpack === null) {
            return response()->json([
                'error' => 'Modpack does not exist',
            ], 404);
        }

        if ($this->requestHasValidKey()) {
            $build = Build::whereIn('status', ['private', 'public'])
                ->whereVersion($build)
                ->whereModpackId($modpack->id)
                ->with('releases', 'releases.package')
                ->first();
        } elseif (request()->has('cid')) {
            $build = Build::whereIn('status', ['private', 'public'])
                ->whereVersion($build)
                ->whereModpackId($modpack->id)
                ->with('releases', 'releases.package')
                ->first();
        } else {
            $build = Build::public()
                ->whereVersion($build)
                ->whereModpackId($modpack->id)
                ->with('releases', 'releases.package')
                ->first();
        }

        if ($build === null) {
            return response()->json([
                'error' => 'Build does not exist',
            ], 404);
        }

        return response()->json([
            'minecraft' => $build->minecraft,
            'mods' => $build->releases->transform(function ($release, $key) {
                return [
                    'name' => $release->package->name,
                    'version' => $release->version,
                    'md5' => $release->md5,
                    'url' => $release->url,
                ];
            }),
        ]);
    }

    /**
     * @return bool
     */
    private function requestHasValidKey(): bool
    {
        return request()->has('k') && Key::isValid(request()->get('k'));
    }
}
