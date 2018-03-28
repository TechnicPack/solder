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
use Illuminate\Support\Facades\Storage;
use App\Facades\FileHash;

class ModpackBuildController extends Controller
{
    /**
     * Return a JSON response containing the releases for a given
     * Modpack slug and Build version.
     *
     * @param string $slug
     * @param string $version
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
        if(isset($build->forge_version)){
                $forge = [
                    'name' => 'Forge',
                    'version' => $build->forge_version,
                    'md5' => url('/storage/forge/') . "/" . $build->minecraft_version . "-" . $build->forge_version . ".zip",
                    'url' => url('/storage/forge/') . "/" . $build->minecraft_version . "-" . $build->forge_version . ".zip"
                ];
       }else{

           $forge = "";
       }





        return response()->json([
            'minecraft' => $build->minecraft_version,
            'java' => $build->java_version,
            'memory' => (int) $build->required_memory,
            'forge' => $build->forge_version,
            'mods' => $forge,
            $build->releases->transform(function ($release) {
                return [
                    'name' => $release->package->name,
                    'version' => $release->version,
                    'md5' => $release->md5,
                    'url' => $release->url,
                ];
            }),
        ]);
    }
}
