<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use App\Build;
use App\Token;
use App\Modpack;
use App\Resource;
use App\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegacyController extends ApiController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        Auth::shouldUse('legacy');
    }

    public function verify($token)
    {
        $client = Token::whereToken($token)->firstOrFail();

        return response()->json([
            'name' => $client->name,
            'valid' => 'Key validated.',
        ]);
    }

    public function listModpacks(Request $request)
    {
        // TODO: There is a bug here that ALL builds are coming back, need to Auth::check() yo'self
        $modpacks = Modpack::ignorePrivacy(Auth::check())->with('builds')->get();

        $transformer = $request->query('include');

        $response = $modpacks->keyBy('slug')->transform(function ($modpack) use ($transformer) {
            if ($transformer == 'full') {
                return $this->transformModpack($modpack);
            }

            return $modpack->name;
        });

        return response()->json([
            'modpacks' => $response,
            'mirror_url' => config('app.url'),
        ]);
    }

    public function showModpack($slug)
    {
        $modpack = Modpack::whereSlug($slug)->ignorePrivacy(Auth::check())->first();

        if ($modpack === null) {
            return response()->json([
                'error' => 'Modpack does not exist.',
            ], 404);
        }

        return response()->json($this->transformModpack($modpack));
    }

    public function showBuild($slug, $version)
    {
        $modpack = Modpack::whereSlug($slug)->ignorePrivacy(Auth::check())->first();

        if ($modpack === null) {
            return response()->json([
                'error' => 'Modpack does not exist.',
            ], 404);
        }

        $build = Build::whereVersion($version)->where('modpack_id', $modpack->id)->ignorePrivacy(Auth::check())->first();

        if ($build === null) {
            return response()->json([
                'error' => 'Build does not exist.',
            ], 404);
        }

        return response()->json($this->transformBuild($build));
    }

    public function listMods()
    {
        $mods = Resource::all();

        $response = $mods->transform(function ($mod) {
            return $this->transformMod($mod);
        });

        return response()->json([
            'mods' => $response,
            'mirror_url' => config('app.url'),
        ]);
    }

    public function showMod($slug)
    {
        $mod = Resource::whereSlug($slug)->first();

        if ($mod === null) {
            return response()->json([
                'error' => 'Mod does not exist.',
            ], 404);
        }

        return response()->json($this->transformMod($mod));
    }

    public function showVersion($slug, $version)
    {
        $mod = Resource::whereSlug($slug)->first();

        if ($mod === null) {
            return response()->json([
                'error' => 'Mod does not exist.',
            ], 404);
        }

        $version = Version::whereVersion($version)->where('resource_id', $mod->id)->first();

        if ($version === null) {
            return response()->json([
                'error' => 'Version does not exist.',
            ], 404);
        }

        return response()->json($this->transformVersion($version));
    }

    /**
     * Transform the Modpack entity.
     * @param Modpack $modpack
     * @return array
     */
    private function transformModpack(Modpack $modpack)
    {
        return [
            'name' => $modpack->slug,
            'display_name' => $modpack->name,
            'url' => $modpack->website,
            'icon' => $modpack->icon,
            'icon_md5' => null,
            'logo' => $modpack->logo,
            'logo_md5' => null,
            'background' => $modpack->background,
            'background_md5' => null,
            'recommended' => null,
            'latest' => null,
            'builds' => $modpack->builds->pluck('version'),
        ];
    }

    /**
     * Transform the Version entity.
     * @param Build $build
     * @return array
     */
    private function transformBuild(Build $build)
    {
        return [
            'minecraft' => $build->game_version,
            'forge' => isset($build->arguments['forge']) ? $build->arguments['forge'] : null,
            'java' => isset($build->arguments['java']) ? $build->arguments['java'] : null,
            'memory' => isset($build->arguments['memory']) ? $build->arguments['memory'] : null,
            'mods' => $build->versions->transform(function ($version) {
                return $this->transformMod($version);
            }),
        ];
    }

    /**
     * Transform the Modpack entity.
     *
     * @param Resource $mod
     *
     * @return array
     */
    private function transformMod(Resource $mod)
    {
        return [
            'name' => $mod->slug,
            'pretty_name' => $mod->name,
            'author' => $mod->author,
            'description' => $mod->description,
            'link' => $mod->website,
            'donate' => null,
            'versions' => $mod->versions->pluck('version'),
        ];
    }

    /**
     * Transform the Modpack entity.
     *
     * @param Version $version
     *
     * @return array
     */
    private function transformVersion(Version $version)
    {
        return [
            'md5' => null, // TODO: need to build zip resource for this...
            'url' => null, // TODO: need to build zip resource for this...
            'filesize' => null,  // TODO: need to build zip resource for this...
        ];
    }
}
