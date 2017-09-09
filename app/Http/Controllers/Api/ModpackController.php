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
use App\Modpack;
use App\Http\Controllers\Controller;

class ModpackController extends Controller
{
    public function index()
    {
        $modpacks = Modpack::whereToken(request()->get('k'), request()->get('cid'))->get();

        return response()->json([
            'modpacks' => $modpacks->keyBy('slug')->transform(function ($modpack) {
                if (request()->query('include') == 'full') {
                    return $this->formatModpack($modpack, $this->requestHasValidKey());
                }

                return $modpack->name;
            }),
            'mirror_url' => config('services.technic.repo', request()->getHttpHost().'/repo/'),
        ]);
    }

    public function show($slug)
    {
        $showPrivate = false;
        if ($this->requestHasValidKey() || request()->has('cid')) {
            $showPrivate = true;
        }

        $modpack = Modpack::with('builds')
            ->where('slug', $slug)
            ->whereToken(request()->get('k'), request()->get('cid'))
            ->first();

        if ($modpack === null) {
            return response()->json([
                'error' => 'Modpack does not exist',
            ], 404);
        }

        return response()->json($this->formatModpack($modpack, $showPrivate));
    }

    /**
     * @param $modpack
     *
     * @param bool $includePrivate
     *
     * @return array
     */
    private function formatModpack($modpack, $includePrivate = false): array
    {
        return [
            'name' => $modpack->slug,
            'display_name' => $modpack->name,
            'recommended' => $modpack->promoted_build->version,
            'latest' => $modpack->latest_build->version,
            'builds' => $modpack->builds->filter(function ($value) use ($includePrivate) {
                return $value->status == 'public' || ($includePrivate && $value->status == 'private');
            })->pluck('version'),
        ];
    }

    /**
     * @return bool
     */
    private function requestHasValidKey(): bool
    {
        return request()->has('k') && Key::isValid(request()->get('k'));
    }
}
