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

use App\Build;
use App\Modpack;
use App\Package;

class ModpackBuildsController extends Controller
{
    public function show($modpackSlug, $buildVersion)
    {
        $modpack = Modpack::where('slug', $modpackSlug)
            ->with(['builds' => function ($query) {
                $query->orderBy('version', 'desc');
            }])
            ->firstOrFail();

        $build = Build::with(['releases.package', 'releases' => function ($query) {
            $query->join('packages', 'releases.package_id', '=', 'packages.id')
                ->orderBy('packages.name');
        }])
            ->where('modpack_id', $modpack->id)
            ->where('version', $buildVersion)
            ->firstOrFail();

        return view('builds.show', [
            'modpack' => $modpack,
            'build' => $build,
            'packages' => Package::orderBy('name')->get(),
        ]);
    }
}
