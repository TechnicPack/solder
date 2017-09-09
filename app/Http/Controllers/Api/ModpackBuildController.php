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
use App\Modpack;
use App\Http\Controllers\Controller;

class ModpackBuildController extends Controller
{
    public function show($slug, $version)
    {
        $modpack = Modpack::with('builds')
            ->where('slug', $slug)
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->first();

        if ($modpack === null) {
            return response()->json([
                'error' => 'Modpack does not exist',
            ], 404);
        }

        $build = Build::with('releases', 'releases.package')
            ->whereVersion($version)
            ->whereModpackId($modpack->id)
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->first();

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
