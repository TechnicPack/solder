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
use Illuminate\Validation\Rule;

class ModpackBuildsController extends Controller
{
    /**
     * Show the modpack build.
     *
     * @param $modpackSlug
     * @param $buildVersion
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * Show the build create form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($modpackSlug)
    {
        $modpack = Modpack::where('slug', $modpackSlug)
            ->with(['builds' => function ($query) {
                $query->orderBy('version', 'desc');
            }])
            ->first();

        return view('builds.create', [
            'modpack' => $modpack,
        ]);
    }

    public function store($modpackSlug)
    {
        $modpack = Modpack::where('slug', $modpackSlug)->first();

        $build = $modpack->builds()->create(request()->validate([
            'version' => ['required', 'regex:/^[a-zA-Z0-9_-][.a-zA-Z0-9_-]*$/', Rule::unique('builds')->where('modpack_id', $modpack->id)],
            'minecraft' => ['required'],
            'status' => ['required', 'in:public,private,draft'],
        ]));

        return redirect("/modpacks/$modpackSlug/{$build->version}");
    }
}
